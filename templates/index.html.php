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

<?= $render('validate-rel-me.html', $render) ?>

<?= $render('validate-h-card.html', $render) ?>

<?= $render('validate-h-entry.html', $render) ?>

<?= $render('send-webmentions.html', $render) ?>

<!-- Level 3 -->
<div class="row demo-row">

	<h1><span class="fui-chat"></span> Federating IndieWeb Conversations <small>Level 3</small></h1>

	<h2>1. Add <strong>Reply Contexts</strong> to your site</h2>
	
	<p class="lede">Posting <a href="http://indiewebcamp.com/reply">replies</a> to other people’s posts is the next step after just being able to mention them with webmention.</p>
	
	<p>Usually a reply is a note just like any other, but linking in a special way to the post it’s in reply to. When marked up with h-entry and <code>rel=in-reply-to</code> and/or <code>class=u-in-reply-to</code>, your reply can show up as a comment on the original post.</p>
	
	<p>To test if your webmention sending is working, try replying to a post by someone who’s implemented comment receiving. There’s a list <a href="http://indiewebcamp.com/webmention#IndieWeb_implementations">on the wiki</a>.</p>
	
	<p>On the wiki: <a href="http://indiewebcamp.com/in-reply-to">in-reply-to</a></p>
	
	<p>If you wish you can also go the extra mile and display a copy of the post you’re replying to. This is called a <a href="http://indiewebcamp.com/reply-context">reply context</a>.</p>

	<h2>2. Receive WebMentions to your site</h2>
	<p>Now you can post replies which show up as comments on other people’s sites, the next step is to be able to receive comments yourself. There are several ways to do this.</p>
	
	<ul>
		<li>If you’re using a project like WordPress, there might already be a plugin enabling receiving of indieweb comments. There’s a list of projects <a href="http://indiewebcamp.com/projects">on the wiki</a></li>
		<li>If you’re rolling your own project and want to implement webmention yourself, have a read through <a href="http://webmention.org">the spec</a> and <a href="http://indiewebcamp.com/webmention">the wiki page</a> for tips</li>
		<li>If you want to get started quickly without implementing receiving of webmentions yourself, take a look at a hosted service like <a href="http://webmention.io/">webmention.io</a></li>
	</ul>

	<!--<h4>Send a test <strong>WebMention</strong> to your website: <span>http://yourwebsite.com</span></h4>
	<div class="row">
		<div class="span5">
			<textarea name="web_mention" rows="4" class="span5"></textarea>
		</div>
	</div>
	<div class="row">
		<div class="span3">
			<a href="#fakelink" class="btn btn-large btn-block btn-primary">Send WebMention</a>
		</div>
	</div>-->

</div>