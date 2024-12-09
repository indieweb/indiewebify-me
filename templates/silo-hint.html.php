<?php namespace Indieweb\IndiewebifyMe; ?>

<?php if (isWordpressDomain($url)) : ?>
    <div class="silo-hint">
        <p>It looks like your site is hosted on the <a href="https://indieweb.org/WordPress.com">WordPress.com Silo</a> without a custom domain name. In order to really own your content you need to own your URLs, but don’t worry — here are some resources which might help you out:</p>
        <ol>
            <li><a href="http://en.support.wordpress.com/domains/">Wordpress Support article on Domains</a></li>
            <li><a href="https://store.wordpress.com/premium-upgrades/custom-domains/">Wordpress.com custom domain premium upgrade</a></li>
        </ol>
        <p>Once you’ve got your own domain, check out the IndieWeb <a href="https://indieweb.org/Wordpress">WordPress wiki page</a> for tips and plugins for adding things like webmentions and microformats to your site.</p>
    </div>

<?php elseif (isTumblrDomain($url)) : ?>
    <div class="silo-hint">
        <p>It looks like your site is hosted on <a href="https://indieweb.org/Tumblr">Tumblr.com Silo</a> without a custom domain name. In order to really own your content you need to own your URLs, but don’t worry — Tumblr has <a href="http://www.tumblr.com/docs/en/custom_domains">an article</a> demonstrating how to give your web presence your own domain.</p>
        <p>Once you’ve got your own domain, check out the IndieWeb <a href="https://indieweb.org/Tumblr">Tumblr wiki page</a> for tips on adding indieweb functionality to your site, or exporting your data elsewhere.</p>
    </div>

<?php elseif (isGithubDomain($url)) : ?>
    <div class="silo-hint">
        <p>It looks like your site is hosted on <a href="https://indieweb.org/Github">Github.io Silo</a> without a custom domain name. In order to really own your content you need to own your URLs, but don’t worry — Github has <a href="https://help.github.com/articles/setting-up-a-custom-domain-with-pages">an article</a> demonstrating how to give your web presence your own domain.</p>
        <p>Once you’ve got your own domain, check out the IndieWeb <a href="https://indieweb.org/Github">Github wiki page</a> for tips on adding indieweb functionality to your site.</p>
    </div>

<?php elseif (isset($bloggingSoftware) and $bloggingSoftware === 'wordpress') : ?>
    <p>It looks like you’re using WordPress to power your site — check out the IndieWeb <a href="https://indieweb.org/Wordpress">WordPress wiki page</a> for tips on how to indiewebify your WordPress site!</p>

<?php elseif (isset($bloggingSoftware) and $bloggingSoftware === 'mediawiki') : ?>
    <p>It looks like you’re using MediaWiki to power your site — check out the IndieWeb <a href="https://indieweb.org/MediaWiki">MediaWiki wiki page</a> for tips on how to indiewebify your MediaWiki site!</p>

<?php elseif (isset($bloggingSoftware) and $bloggingSoftware === 'idno') : ?>
    <p>It looks like you’re using idno to power your site — check out the IndieWeb <a href="https://indieweb.org/Idno">idno wiki page</a> for tips on how to indiewebify your idno site!</p>
<?php endif ?>
