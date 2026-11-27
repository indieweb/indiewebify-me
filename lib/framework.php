<?php
/**
 * The small amount of "framework" indiewebify-me needs, replacing Silex 1.3.
 *
 * Behaviour deliberately mirrors what Silex 1.3.5 / Symfony 2.8 produced, so
 * the site's responses stay byte-identical (verified by
 * tests/characterization against a PHP 7.4 baseline):
 *
 *  - exact-path routes; HEAD is served by GET routes (body stripped);
 *  - a GET/HEAD for a path without its trailing slash, when the slashed path
 *    is a route, gets a 301 to the slashed path plus the raw query string
 *    (Silex\RedirectableUrlMatcher);
 *  - a path that exists for other methods gets a 405 with `Allow`, in route
 *    registration order; anything else gets a 404;
 *  - 404 / 405 / 500 error pages use the site layout (templates/error.html.php);
 *    the status codes match what Silex sent, the page content does not;
 *  - uncaught \Exception from a controller becomes that 500 page; \Error is
 *    left alone (Silex 1.3 never caught it, so PHP's own fatal 500 results);
 *  - responses with no cache headers get `Cache-Control: no-cache` (Symfony
 *    2.8), not Symfony 7's `no-cache, private`;
 *  - redirect bodies use the Symfony 2.8 RedirectResponse markup.
 */

namespace Indieweb\IndiewebifyMe;

use GuzzleHttp;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class Router {
	/** @var array<string, array<string, callable>> path => method => handler */
	private array $routes = [];

	public function get(string $path, callable $handler): void {
		$this->routes[$path]['GET'] = $handler;
	}

	public function post(string $path, callable $handler): void {
		$this->routes[$path]['POST'] = $handler;
	}

	public function handle(Request $request): Response {
		$path = $request->getPathInfo();
		$method = $request->getMethod();
		$lookup = $method === 'HEAD' ? 'GET' : $method;

		if (isset($this->routes[$path][$lookup])) {
			try {
				$result = ($this->routes[$path][$lookup])($request);
			} catch (\Exception $e) {
				return errorPage(500);
			}
			return $result instanceof Response ? $result : new Response((string) $result);
		}

		if (isset($this->routes[$path])) {
			$response = errorPage(405);
			$response->headers->set('Allow', implode(', ', array_keys($this->routes[$path])));
			return $response;
		}

		if (substr($path, -1) !== '/' and in_array($method, ['GET', 'HEAD'], true) and isset($this->routes[$path . '/']['GET'])) {
			$url = $request->getBaseUrl() . $path . '/';
			$query = (string) $request->server->get('QUERY_STRING', '');
			if ($query !== '') {
				$url .= '?' . $query;
			}
			return redirect($url, 301);
		}

		return errorPage(404);
	}

	public function run(): void {
		$request = Request::createFromGlobals();
		emit($this->handle($request), $request);
	}
}

function renderTemplate($template, array $__templateData = array()) {
	$render = function ($__path, $render=null) use ($__templateData) {
		ob_start();
		extract($__templateData);
		unset($__templateData);
		include __DIR__ . '/../templates/' . $__path . '.php';
		return ob_get_clean();
	};

	return $render($template, $render);
}

function render($template, array $data = array()) {
	$isHtml = pathinfo($template, PATHINFO_EXTENSION) === 'html';
	$out = '';

	if ($isHtml)
		$out .= renderTemplate('header.html', $data);
	$out .= renderTemplate($template, $data);
	if ($isHtml)
		$out .= renderTemplate('footer.html', $data);
	return $out;
}

/**
 * Error page in the site layout, for 404s, 405s and uncaught exceptions.
 */
function errorPage(int $status): Response {
	[$title, $message] = match ($status) {
		404 => ['Page not found', 'Sorry, the page you are looking for could not be found.'],
		405 => ['Method not allowed', 'This page can’t be requested that way.'],
		default => ['Something went wrong', 'Whoops, looks like something went wrong on our end. Please try again later.'],
	};
	return new Response(render('error.html', ['status' => $status, 'title' => $title, 'message' => $message]), $status);
}

function redirect(string $url, int $status = 302): Response {
	$body = sprintf('<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="refresh" content="1;url=%1$s" />

        <title>Redirecting to %1$s</title>
    </head>
    <body>
        Redirecting to <a href="%1$s">%1$s</a>.
    </body>
</html>', htmlspecialchars($url, ENT_QUOTES, 'UTF-8'));

	return new Response($body, $status, ['Location' => $url]);
}

function emit(Response $response, Request $request): void {
	$response->setCharset('UTF-8'); // Symfony 7 defaults to "utf-8"; 2.8 sent "UTF-8"
	$response->prepare($request);
	$status = $response->getStatusCode();

	if (!headers_sent()) {
		header(sprintf('HTTP/%s %s %s', $response->getProtocolVersion(), $status, Response::$statusTexts[$status] ?? ''), true, $status);
		foreach ($response->headers->allPreserveCaseWithoutCookies() as $name => $values) {
			if (strcasecmp($name, 'Cache-Control') === 0 and $values === ['no-cache, private']) {
				$values = ['no-cache'];
			}
			foreach ($values as $value) {
				header($name . ': ' . $value, false, $status);
			}
		}
	}

	$response->sendContent();
}

/**
 * Value of a request parameter as Symfony 2.8's Request::get() found it:
 * route attributes, then the query string, then the POST body.
 */
function requestParam(Request $request, string $key, $default = null) {
	foreach ([$request->attributes, $request->query, $request->request] as $bag) {
		if ($bag->has($key)) {
			return $bag->all()[$key];
		}
	}
	return $default;
}

/**
 * A fetched page, exposing the parts of the Guzzle 3 Response API the app used.
 */
final class FetchedPage {
	public function __construct(
		private int $status,
		private string $body,
		private string $effectiveUrl,
	) {}

	public function getStatusCode(): int {
		return $this->status;
	}

	public function getBody($asString = false): string {
		return $this->body;
	}

	public function getEffectiveUrl(): string {
		return $this->effectiveUrl;
	}
}

/**
 * GET a URL the way the Guzzle 3 client did: follow up to 5 redirects, treat
 * 4xx/5xx as errors. Returns [FetchedPage|null, \Exception|null]; error
 * messages use Guzzle 3's wording, since they are shown to the user.
 */
function fetchUrl(string $url, ?GuzzleHttp\ClientInterface $client = null): array {
	if (parse_url($url) === false) {
		return [null, new \RuntimeException('Was unable to parse malformed url: ' . $url)];
	}

	$client ??= new GuzzleHttp\Client();

	try {
		$response = $client->request('GET', $url, [
			'allow_redirects' => ['max' => 5, 'track_redirects' => true],
			'http_errors' => true,
		]);
	} catch (GuzzleHttp\Exception\BadResponseException $e) {
		$response = $e->getResponse();
		$status = $response->getStatusCode();
		$label = $status >= 400 && $status < 500 ? 'Client error response'
			: ($status >= 500 && $status < 600 ? 'Server error response' : 'Unsuccessful response');
		return [null, new \RuntimeException($label . PHP_EOL . implode(PHP_EOL, [
			'[status code] ' . $status,
			'[reason phrase] ' . $response->getReasonPhrase(),
			'[url] ' . (string) $e->getRequest()->getUri(),
		]), 0, $e)];
	} catch (GuzzleHttp\Exception\TransferException $e) {
		// Guzzle 8 reports cURL failures as "cURL error N: <curl_error> (see https://...)[ for <url>]".
		if (preg_match('/^cURL error (\d+): (.*) \(see https:\/\/curl\.se\/libcurl\/c\/libcurl-errors\.html\)/s', $e->getMessage(), $m)) {
			return [null, new \RuntimeException(sprintf('[curl] %s: %s [url] %s',
				$m[1], $m[2], (string) $e->getRequest()->getUri()), 0, $e)];
		}
		return [null, $e];
	} catch (\InvalidArgumentException $e) {
		return [null, new \RuntimeException('Was unable to parse malformed url: ' . $url, 0, $e)];
	}

	$history = $response->getHeader('X-Guzzle-Redirect-History');
	$effectiveUrl = $history ? end($history) : $url;

	return [new FetchedPage($response->getStatusCode(), (string) $response->getBody(), $effectiveUrl), null];
}
