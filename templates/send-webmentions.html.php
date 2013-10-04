<div id="send-webmentions" class="row demo-row">
	<h2>2. Add the ability to send a <a href="http://webmention.org" target="_blank">WebMentions</a> to other IndieWeb sites</h2>
	
	<p class="lead">When you reply to something on another indieweb site, or mention someone with an indieweb site, sending a webmention lets them know they’ve been linked to.</p>
	
	<p>Sending webmentions allows you to write replies to other content and participate in cross-site conversations.</p>
	
	<ul>
		<li>Send webmentions and pingbacks manually using the form below</li>
		<li>Send webmentions from the command line <a href="http://indiewebcamp.com/webmention#How_to_Test_Webmentions">using Curl</a> or <a href="https://github.com/vrypan/webmention-tools">webmention-tools</a></li>
		<li>Make your publishing software send webmentions for you automatically using one of the <a href="http://indiewebcamp.com/webmention#Webmention-related_libraries_and_tools">open source libraries</a></li>
		<li>Or, get down and dirty with the <a href="http://webmention.org">WebMention spec</a> and implement it yourself</li>
	</ul>
	
	<p>On the wiki: <a href="http://indiewebcamp.com/webmention">webmention</a></p>
	
	<? if ($error or $numSent): ?>
	<div class="result alert <? if ($error): ?>alert-warning<? else: ?>alert-success<? endif ?>">
		<? if ($error): ?>
		<h4>Something Went Wrong!</strong></h4>
		<p>When sending webmentions and pingbacks for <code><?= $url ?></code>, we got this problem:</p>
		<p><?= $error['message'] ?></p>
		<? elseif ($numSent): ?>
		<p>Successfully sent <?= $numSent ?> webmentions/pingbacks from <code><?= $url ?></code></p>
		<? endif ?>
	</div>
	<? endif ?>
	
	<form class="row" action="/send-webmentions/" method="get">
		<div class="span4">
			<input class="span4" type="text" value="<?= $url ?>" name="url" placeholder="http://yoursite.com" />
		</div>
		<div class="span3">
			<button type="submit"class="btn btn-large btn-block btn-primary">Send Mentions</button>
		</div>
	</form>
</div>
