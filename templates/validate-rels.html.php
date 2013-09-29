<div class="row demo-row">
	<h1><span class="fui-user"></span> Become a citizen of the IndieWeb <small>Level 1</small></h1>

	<h2>1. Register a domain name to use as your personal web identity</h2>
	<h2>2. Add ability to sign in <a href="http://indiewebcamp.com/IndieAuth" target="_blank">IndieAuth</a> using your personal domain name</h2>

	<h4>Once you've added the proper rel="me" links test out signing in with IndieAuth</h4>
	
	<div class="result">
		<? if ($error): ?>
		<h4>Something Went Wrong!</strong></h4>
		<p>When fetching <code><?= $url ?></code>, we found this problem:</p>
		<p><?= $error['message'] ?></p>
		<? elseif ($rels): ?>
		<h4>Success!</h4>
		
		<p>We found the following <code>rel=me</code> URLs on your site:</p>
		
		<ul>
			<? foreach ($rels as $rel): ?>
			<li><?= $rel ?></li>
			<? endforeach ?>
		</ul>
		<? endif ?>
	</div>
	
	<form class="row" action="/validate-rels" method="get">
		<div class="span4">
			<input class="span4" type="text" value="<?= $url ?>" name="url" placeholder="http://yoursite.com" />
		</div>
		<div class="span3">
			<button type="submit"class="btn btn-large btn-block btn-primary">Test</button>
		</div>
	</form>
</div>
