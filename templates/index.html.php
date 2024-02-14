
<div class="row">
	<div class="span12">
		<div class="demo-headline">
			<h1 class="demo-logo">
				Indie<wbr>Webify<wbr>.Me
				<small>A guide to getting you on the <a href="https://indieweb.org" target="_blank">IndieWeb</a></small>
			</h1>
		</div> <!-- .demo-headline -->
	</div><!--/.span-->
</div><!--/.row-->

<div class="row demo-row">
	<div class="span12">
		<h1>What is the IndieWeb?</h1>

		<blockquote>
			We should all own the content we're creating, rather than just posting to third-party content silos.Publish on your own domain, and syndicate out to silos. This is the basis of the "Indie Web" movement. – <a href="https://indieweb.org" target="_blank">IndieWeb.org</a>
		</blockquote>
	</div><!--/.span-->
</div> <!-- .row.demo-row -->

<hr>

<?= $render('validate-rel-me.html', $render) ?>

<?= $render('validate-h-card.html', $render) ?>

<?= $render('validate-h-entry.html', $render) ?>

<?= $render('send-webmentions.html', $render) ?>

<!-- Level 3 -->
<div class="row demo-row">
	<div class="span12">

	<h1><span class="fui-chat"></span> Federating IndieWeb Conversations <small>Level 3</small></h1>

	<h2>1. Add <strong>Reply Contexts</strong> to your site</h2>
	
	<p class="lead">Posting <a href="https://indieweb.org/reply">replies</a> to other people’s posts is the next step after just being able to mention them with webmention.</p>
	
	<p>Usually a reply is a <a href="https://indieweb.org/note">note</a> just like any other, but linking in a special way to the post it’s in reply to. When marked up with h-entry and <code>rel=in-reply-to</code> and/or <code>class=u-in-reply-to</code>, your reply can show up as a comment on the original post.</p>
	
	<p>To test if your webmention sending is working, try replying to a post by someone who’s implemented comment receiving. There’s a list <a href="https://indieweb.org/webmention#IndieWeb_implementations">on the wiki</a>.</p>

	<p>If you wish you can also go the extra mile and display a copy of the post you’re replying to. This is called a <a href="https://indieweb.org/reply-context">reply context</a>, and is an excellent way to practise parsing posts on other people’s sites.</p>

	<h2>2. Receive webmentions on your site</h2>
	<p>Now you can post replies which show up as comments on other people’s sites, the next step is to be able to receive comments yourself. There are several ways to do this.</p>
	
	<ul>
		<li>If you’re using a project like <a href="http://withknown.com">Known</a>, it may already support indieweb comments — you don’t have to do anything!</li>
		<li>If you’re using a project like WordPress, there may already be a plugin enabling receiving of indieweb comments. See if the software you’re using is on the <a href="https://indieweb.org/projects">project list on the wiki</a></li>
		<li>If you’re rolling your own project and want to implement webmention yourself, have a read through <a href="http://webmention.net">the spec</a> and <a href="https://indieweb.org/webmention">the wiki page</a> for tips</li>
		<li>If you want to get started quickly without implementing receiving of webmentions yourself, take a look at a hosted service like <a href="http://webmention.io/">webmention.io</a></li>
	</ul>

	<p>Once you’ve got webmention receiving set up, there are a few different ways of making sure it’s working correctly:</p>

	<ul>
		<li><strong>Link to one of your own posts</strong> and send yourself a mention. This works best if you know you can send webmentions successfully</li>
		<li><strong>Ask a friend</strong> (or a new friend in the <a href="https://indieweb.org/IRC">online #indieweb chat</a> to reply or mention one of your posts</li>
		<li>If you’re POSSEing your content, setting up <a href="https://indieweb.org/backfeed">backfeed</a> so that silo replies, likes, reshares, and event RSVPs show up on your own site. You can use a service like <a href="https://www.brid.gy/">Bridgy</a>, a <a href="https://indieweb.org/backfeed#WordPress_Plugins">server plugin</a>, or roll your own</li>
	</ul>

	</div><!--/.span-->
</div> <!-- .row.demo-row -->

<div class="row">
<footer class="span12">
	<p>Want to make changes to IndieWebify.Me? The code is <a href="https://github.com/indieweb/indiewebify-me/">on Github</a>. Found a problem? <a href="https://github.com/indieweb/indiewebify-me/issues/">file an issue or send a PR</a>.</p>
</footer>
</div>
