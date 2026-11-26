<?php
/**
 * Fixture "internet" for the characterization harness.
 *
 * Run with a fixed PHP version so its output never depends on the PHP the
 * app under test runs on:
 *
 *   php8.6 -S 127.0.0.1:8290 tests/characterization/fixtures.php
 *
 * Every request it receives is appended as one JSON line to the file named
 * by the FIXTURE_LOG env var, so the harness can record the app's outbound
 * requests (fetches, webmentions, pingbacks) alongside each response.
 */

$base = 'http://' . $_SERVER['HTTP_HOST'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

if ($log = getenv('FIXTURE_LOG')) {
	file_put_contents($log, json_encode([
		'method' => $method,
		'uri' => $_SERVER['REQUEST_URI'],
		'content_type' => $_SERVER['CONTENT_TYPE'] ?? null,
		'body' => file_get_contents('php://input'),
	], JSON_UNESCAPED_SLASHES) . "\n", FILE_APPEND | LOCK_EX);
}

function page($body, $head = '') {
	header('Content-Type: text/html; charset=utf-8');
	echo "<!doctype html>\n<html><head><title>fixture</title>$head</head><body>\n$body\n</body></html>\n";
	exit;
}

function redirect($to, $code = 301) {
	http_response_code($code);
	header("Location: $to");
	exit;
}

switch ($path) {
	// ---- h-card ----
	case '/hcard/representative':
		page(<<<HTML
<div class="h-card">
  <img class="u-photo" src="$base/photo.jpg" alt="">
  <a class="u-url u-uid p-name" href="$base/hcard/representative">Rep Resentative</a>
  <a class="u-url" href="https://social.example/@rep">social</a>
  <span class="p-nickname">rep</span>
  <span class="p-note">Hello there</span>
  <span class="p-locality">Portland</span>
</div>
HTML);
	case '/hcard/relme':
		page(<<<HTML
<a class="h-card u-url p-name" rel="me" href="$base/hcard/relme">Rel Me Card</a>
HTML);
	case '/hcard/nonrep':
		page(<<<HTML
<div class="h-card"><a class="p-name u-url" href="https://elsewhere.example/">Not Me</a>
<img class="u-logo" src="$base/logo.png" alt=""></div>
HTML);
	case '/hcard/multi':
		page(<<<HTML
<div class="h-card"><span class="p-name">First Card</span></div>
<div class="h-card"><span class="p-name">Second Card</span></div>
HTML);
	case '/hcard/none':
		page('<p>No microformats here.</p>');
	case '/hcard/escaping':
		page(<<<HTML
<div class="h-card"><a class="u-url u-uid" href="$base/hcard/escaping"><span class="p-name">O'Brien &amp; "Quotes" &lt;b&gt;</span></a>
<span class="p-org">Café Ünïcödé — ☕</span></div>
HTML);
	case '/hcard/empty-name':
		page('<div class="h-card"><a class="u-url u-uid" href="' . $base . '/hcard/empty-name"></a></div>');

	// ---- h-entry ----
	case '/hentry/note':
		page('<div class="h-entry"><div class="e-content p-name">Just a note</div><a class="u-url" href="' . $base . '/hentry/note">link</a></div>');
	case '/hentry/article':
		page('<article class="h-entry"><h1 class="p-name">A Title</h1><div class="e-content">Body text that is long enough</div><time class="dt-published" datetime="2013-06-30T12:00:00Z">then</time></article>');
	case '/hentry/reply':
		page('<div class="h-entry"><a class="u-in-reply-to" href="https://other.example/post">re</a><p class="e-content">Reply text</p></div>');
	case '/hentry/like':
		page('<div class="h-entry"><a class="u-like-of" href="https://other.example/post">liked</a></div>');
	case '/hentry/repost':
		page('<div class="h-entry"><a class="u-repost-of" href="https://other.example/post">reposted</a></div>');
	case '/hentry/name-invalid':
		page('<div class="h-entry"><span class="p-name">A very much longer name than the content itself</span><div class="e-content">Short</div></div>');
	case '/hentry/value-only':
		page('<div class="h-entry">Only implied value text</div>');
	case '/hentry/none':
		page('<p>Nothing here</p>');
	case '/hentry/unicode':
		page('<div class="h-entry"><div class="e-content">Ünïcödé ☕ content with <b>markup</b> &amp; O\'Quote</div></div>');

	// ---- rel=me ----
	case '/relme/home':
		page(<<<HTML
<a rel="me" href="$base/relme/profile-back">back</a>
<a rel="me" href="$base/relme/profile-noback">noback</a>
<a rel="me" href="$base/relme/profile-redirect">redirecting profile</a>
<a rel="me" href="mailto:me@example.com">email</a>
<a rel="me" href="$base/relme/profile-back">duplicate</a>
HTML);
	case '/relme/profile-back':
		page('<a rel="me" href="' . $base . '/relme/home">my site</a>');
	case '/relme/profile-back-redirect':
		page('<a rel="me" href="' . $base . '/relme/home-redirect">my site via redirect</a>');
	case '/relme/profile-noback':
		page('<a rel="me" href="https://unrelated.example/">someone else</a>');
	case '/relme/profile-redirect':
		redirect("$base/relme/profile-back");
	case '/relme/home-redirect':
		redirect("$base/relme/home");
	case '/relme/none':
		page('<a href="https://example.com/">not rel me</a>');
	case '/relme/wordpress':
		page('<a rel="me" href="' . $base . '/relme/profile-noback">x</a>', '<meta name="generator" content="WordPress 6.5">');
	case '/relme/mediawiki':
		page('<a rel="me" href="' . $base . '/relme/profile-noback">x</a>', '<meta name="generator" content="MediaWiki 1.40">');
	case '/relme/idno':
		page('<a rel="me" href="' . $base . '/relme/profile-noback">x</a>', '<meta name="generator" content="Idno 1.0">');

	// ---- HTTP edge cases ----
	case '/status/404':
		http_response_code(404);
		page('<p>Not found</p>');
	case '/status/500':
		http_response_code(500);
		page('<p>Server error</p>');
	case '/status/410':
		http_response_code(410);
		page('<p>Gone</p>');
	case '/redirect/once':
		redirect("$base/hcard/representative", 302);
	case '/redirect/to-404':
		redirect("$base/status/404", 302);
	case '/redirect/relative':
		redirect('/hentry/note', 302);
	case '/redirect/loop-a':
		redirect("$base/redirect/loop-b", 302);
	case '/redirect/loop-b':
		redirect("$base/redirect/loop-a", 302);
	case '/plain.txt':
		header('Content-Type: text/plain');
		echo "plain text, no html\n";
		exit;

	// ---- webmention ----
	case '/wm/source':
		page(<<<HTML
<div class="h-entry"><div class="e-content">Mentioning
 <a href="$base/wm/target-header">header target</a>,
 <a href="$base/wm/target-html">html target</a>,
 <a href="$base/wm/target-none">no endpoint</a> and
 <a href="$base/wm/target-pingback">pingback target</a>.</div></div>
HTML);
	case '/wm/source-noentry':
		page('<p>Mention <a href="' . $base . '/wm/target-html">html target</a> without h-entry</p>');
	case '/wm/source-nolinks':
		page('<div class="h-entry"><p class="e-content">No links at all</p></div>');
	case '/wm/target-header':
		header('Link: <' . $base . '/wm/endpoint?via=header>; rel="webmention"');
		page('<p>target with header endpoint</p>');
	case '/wm/target-html':
		page('<p>target with html endpoint</p>', '<link rel="webmention" href="/wm/endpoint?via=html">');
	case '/wm/target-none':
		page('<p>target without endpoint</p>');
	case '/wm/target-pingback':
		header('X-Pingback: ' . $base . '/wm/xmlrpc');
		page('<p>pingback target</p>');
	case '/wm/endpoint':
		http_response_code($method === 'POST' ? 202 : 405);
		header('Content-Type: text/plain');
		echo "accepted\n";
		exit;
	case '/wm/xmlrpc':
		header('Content-Type: text/xml');
		echo '<?xml version="1.0"?><methodResponse><params><param><value><string>ok</string></value></param></params></methodResponse>';
		exit;

	case '/photo.jpg':
	case '/logo.png':
		header('Content-Type: image/png');
		echo base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==');
		exit;
}

http_response_code(404);
header('Content-Type: text/plain');
echo "fixture not found: $path\n";
