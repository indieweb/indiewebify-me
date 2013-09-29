<div class="demo-headline">
	<h1 class="demo-logo">
		IndieWebify.Me
		<small>A guide to getting you on the <a href="http://indiewebcamp.com" target="_blank">IndieWeb</a></small>
	</h1>
</div>

<div class="row demo-row">

	<h1>What is the IndieWeb?</h1>

	<blockquote>			
		We should all own the content we're creating, rather than just posting to third-party content silos. 
		Publish on your own domain, and syndicate out to silos.
		This is the basis of the "Indie Web" movement.
		~<a href="http://indiewebcamp.com" target="_blank">IndieWebCamp</a>
	</blockquote>
	
	<p>Want to get the source code? It’s <a href="https://github.com/indieweb/indiewebify-me/">on Github</a>. Found a problem? <a href="https://github.com/indieweb/indiewebify-me/issues/">file an issue or send a PR</a>.</p>

</div>
<div class="row demo-row"><hr></div>


<!-- Level 1 -->
<?= $render('validate-rels.html', $render) ?>


<!-- Level 2 -->
<?= $render('validate-h-card.html', $render) ?>

<?= $render('validate-h-entry.html', $render) ?>

<div class="row demo-row">
	<h2>2. Add the ability to "send" a <a href="http://webmention.org" target="_blank">WebMention</a> to other IndieWeb sites</h2>
	<h4>A Post / Note marked up content needs to exist at the URL with valid Microformat data 
		<h4>Send Webmention POST request to the site you are mentioning</h4>
		<p>&nbsp;</p>

		<ul>
			<li>Use an existing client like <a href="https://github.com/indieweb/mention-client" target="_blank">Webmention Client</a> (PHP)</li>
			<li>Write your own client in your language of choosing ;)</li>
		</ul>

</div>



<!-- Level 3 -->
<div class="row demo-row">

	<h1><span class="fui-chat"></span> Federating IndieWeb Conversations <small>Level 3</small></h1>

	<h2>1. Add <strong>Reply Contexts</strong> to your site</h2>

	<h4>Must have "URL" input</h4>
	<h4>Fetch remote Microformat data from</h4>
	<h4>Store this data on your website</h4>
	<h4>Display this data along with your reply</h4>
	<hr>

	<h2>2. Add Receiving of WebMentions to your site</h2>
	<h4>Setup endpoint of your website to "recieve" webmentions from remote sites</h4>
	<p>&nbsp;</p>

	<h4>Send a test <strong>WebMention</strong> to your website: <span>http://yourwebsite.com</span></h4>
	<div class="row">
		<div class="span5">
			<textarea name="web_mention" rows="4" class="span5"></textarea>
		</div>
	</div>
	<div class="row">
		<div class="span3">
			<a href="#fakelink" class="btn btn-large btn-block btn-primary">Send WebMention</a>
		</div>
	</div>

</div>