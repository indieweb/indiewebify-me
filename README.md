# IndieWebify.me

This tool offers a fast and easy way to get you & your website on the Indieweb, to use go to [indiewebify.me](http://indiewebify.me).

To learn more about the IndieWeb go to [indieweb.org](https://indieweb.org).

Made by Brennan Novak, Barnaby Walters, and others at the 2013 IndieWebCamps in [Reykjavik](http://indieweb.org/2013/#Remote_Indiewebcamp_Parties) and [Brighton](http://indieweb.org/2013/UK).

## Installation

Requirements:
* PHP 8.4 or newer (tested on 8.5 and 8.6), with the curl, dom, mbstring and xml extensions

How to run your own instance of indiewebify.me for development:

1. `git clone https://github.com/indieweb/indiewebify-me.git && cd indiewebify-me`
1. If you don’t have [Composer](https://getcomposer.org) installed, do so:
	* `curl -s https://getcomposer.org/installer | php`
1. Install dependencies: `./composer.phar install`
1. Point your web server at `indiewebify-me/web/`.
	* The included .htaccess should route apache requests correctly. For nginx, send anything that isn’t a file to `index.php`: `try_files $uri /index.php$is_args$args;`
	* For quick local development: `php -S 127.0.0.1:8080 -t web web/index.php`

## Tests

* Unit tests: `vendor/bin/phpunit`
* Characterization tests (HTTP level, against a local fixture server; no network access needed): `tests/characterization/harness.py record --php php8.6 --app . --out /tmp/rec-a`, then `harness.py compare` two recordings. See the docstring in `harness.py`.

All of the interesting stuff is in `/web/index.php` — or at least is until that gets too big and needs moving.



