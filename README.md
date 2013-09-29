# IndieWebify.me

This tool offers a fast and easy way to get you & your website on the Indieweb, to use go to [indiewebify.me](http://indiewebify.me).

To learn more about the IndieWeb go to [indiewebcamp.com](http://indiewebcamp.com).

Made by Brennan Novak, Barnaby Walters, and others at the 2013 IndieWebCamps in [Reykjavik](http://indiewebcamp.com/2013/#Remote_Indiewebcamp_Parties) and [Brighton](http://indiewebcamp.com/2013/UK).

## Installation

Requirements:

* PHP 5.4

How to run your own instance of indiewebify.me for development:

1. `git clone https://github.com/indieweb/indiewebify-me.git && cd indiewebify-me`
1. If you don’t have [Composer](https://getcomposer.org) installed, do so:
	* `curl -s https://getcomposer.org/installer | php`
1. Install dependencies: `./composer.phar install`
1. Point your web server at `indiewebify-me/web/`.
	* The included .htaccess should route apache requests correctly. For other servers file an issue or send us a pull request

All of the interesting stuff is in `/web/index.php` — or at least is until that gets too big and needs moving.
