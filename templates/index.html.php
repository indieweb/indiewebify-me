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

</div>
<div class="row demo-row"><hr></div>


<!-- Level 1 -->
<div class="row demo-row">

	<h1><span class="fui-user"></span> Become a citizen of the IndieWeb <small>Level 1</small></h1>

	<h2>1. Register a domain name to use as your personal web identity</h2>
	<h2>2. Add ability to sign in <a href="http://indiewebcamp.com/IndieAuth" target="_blank">IndieAuth</a> using your personal domain name</h2>
	<p>&nbsp;</p>

	<h4>Once you've added the proper rel="me" links test out signing in with IndieAuth</h4>
	<form class="validate-rel row">
				<div class="span4">
					<input type="text" id="validate-rel-url" value="" placeholder="http://yoursite.com" class="span4" />
				</div>
				<div class="span3">
					<button type="submit" id="validate-rel" class="btn btn-large btn-block btn-primary">Test</button>
				</div>
	</form>
</div>
<div id="validate-rel-result" class="row validate-result"></div>


<!-- Level 2 -->
<div class="row demo-row">

	<h1><span class="fui-new"></span> Publishing on the IndieWeb <small>Level 2</small></h1>

	<h2>1. Markup your content (Posts, Articles, Notes, etc...) with <a href="http://microformats.org/" target="_blank">Microformats2</a></h2>
	<p>&nbsp;</p>

	<h4>Validate your <strong>homepage</strong> has a <a href="http://microformats.org/wiki/h-card" target="_blank">h-card</a> that is properly formatted</h4>
	<form class="validate-h-card row">
				<div class="span4">
					<input type="text" id="validate-h-card-url" value="" placeholder="http://yoursite.com" class="span4" />
				</div>
				<div class="span3">
					<button type="submit" id="validate-h-card" class="btn btn-large btn-block btn-primary">Validate profile h-card</button>
				</div>
	</form>
	<div id="validate-h-card-result" class="row validate-result"></div>
	<p>&nbsp;</p>


	<h4>Validate your <strong>posts / notes</strong> display a <a href="http://microformats.org/wiki/h-entry" target="_blank">h-entry</a> that is properly formatted</h4>
	<form class="validate-h-entry row">
		<div class="span4">
			<input type="text" id="validate-h-entry-url" value="" placeholder="http://yoursite.com/notes/123456" class="span4" />
		</div>
		<div class="span3">
			<button type="submit" id="validate-h-entry" class="btn btn-large btn-block btn-primary">Validate h-entry</button>
		</div>
	</form>
	<div id="validate-h-entry-result" class="row validate-result"></div>
	<p>&nbsp;</p>



	<h2>2. Add the ability to "send" a <a href="http://webmention.org" target="_blank">WebMention</a> to other IndieWeb sites</h2>
	<h4>A Post / Note marked up content needs to exist at the URL with valid Microformat data 
	<h4>Send Webmention POST request to the site you are mentioning</h4>
	<p>&nbsp;</p>

	<ul>
		<li>Use an existing client like <a href="https://github.com/indieweb/mention-client" target="_blank">Webmention Client</a> (PHP)</li>
		<li>Write your own client in your language of choosing ;)</li>
	</ul>

</div>
<div class="row demo-row"><hr></div>



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