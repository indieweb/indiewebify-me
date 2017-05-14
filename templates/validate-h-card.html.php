<?php use BarnabyWalters\Mf2; ?>
<div class="row demo-row">
	<div class="span12">

	<h1><span class="fui-new"></span> Publishing on the IndieWeb <small>Level 2</small></h1>

	<h2>1. Mark up your content (Profile, Notes, Articles, etc…) with <a href="http://microformats.org/" target="_blank">microformats2</a></h2>
	
	<p>Other humans can already understand your profile information, and the things you post on your site. By adding a few simple classnames to your HTML, other people’s software can understand it all too, and use it for things like <a href="https://indieweb.org/reply-context">reply contexts</a>, <a href="https://indieweb.org/comment">cross-site comments</a>, <a href="https://indieweb.org/rsvp">event RSVPs</a> and more.</p>
	
	<h3>Check your <strong>homepage <a href="http://microformats.org/wiki/h-card" target="_blank">h-card</a></strong>:</h3>

	<form class="row" action="/validate-h-card/" method="get">
		<div class="span4">
			<input type="text" id="validate-h-card-url" name="url" value="<?= $url ?>" placeholder="http://yoursite.com" class="span4" />
		</div>
		<div class="span3">
			<button type="submit" id="validate-h-card" class="btn btn-large btn-block btn-primary">Validate h-card</button>
		</div>
	</form>
	
	<?php if ($error or $showResult): ?>
	<div class="result alert <?php if ($error): ?>alert-warning<?php else: ?>alert-success<?php endif ?>">
		<?php if ($error): ?>
			<h3>Something Went Wrong!</h3>
			<p>When fetching <code><?= $url ?></code>, we got this problem:</p>
			<p><?= $error['message'] ?></p>
		<?php else: ?>
			<?php if (count($representativeHCards) == 1): $hCard = $representativeHCards[0]; ?>
				<h3>Success!</h3>
				<p>This representative <code>h-card</code> was found on your site:</p>
			<?php elseif (count($representativeHCards) > 1): $hCard = $representativeHCards[0] ?>
				<h3>Almost there!</h3>
				<p>Multiple representative h-cards were found on your site! Consider only having one. Here’s the first one:</p>
			<?php elseif (count($representativeHCards) == 0 and $firstHCard !== null): $hCard = $firstHCard; ?>
				<h3>Almost there!</h3>
				<p>A h-card was found on your site, but it’s not marked up as the <a href="http://microformats.org/wiki/representative-hcard-parsing">representative h-card</a>!</p>
				<p>Add a <code>u-url</code> property which matches a <code>rel=me</code> link on the same page so this h-card can be identified as the h-card which <em>represents</em> the page.</code></p>
			<?php else: $hCard = null; ?>
				<h3>No h-cards found</h3>
				<p>No h-cards were found on your site! Adding one can be as simple as this:</p>

				<pre><code>&lt;a class=&quot;h-card&quot; rel=&quot;me&quot; href=&quot;<?= $url ?>&quot;>Your Name&lt;/a></code></pre>

				<p>But you can also add other properties for a more detailed profile — see <a href="http://microformats.org/wiki/h-card">h-card on the microformats wiki</a> for a full list.</p>
			<?php endif ?>

			<?php if ($hCard): ?>
			<div class="preview-h-card preview-block">
				<?php if (Mf2\hasProp($hCard, 'photo')): ?>
				<img class="photo-block" src="<?= Mf2\getProp($hCard, 'photo')?>" alt="" />
				<?php else: ?>
				<div class="empty-property-block photo-block">
					<p>Add a photo!</p>
					<p><code>&lt;img class=&quot;u-photo&quot; src=&quot;…&quot /></code></p>
				</div>
				<?php endif ?>
				<p class="p-name"><?= Mf2\getProp($hCard, 'name') ?></p>

				<p class="property-block-name">URL</p>
				<?php if (Mf2\hasProp($hCard, 'url')): ?>
				<ul>
					<?php foreach ($hCard['properties']['url'] as $pUrl): ?>
					<li><a href="<?= $pUrl ?>"><?= $pUrl ?></a></li>
					<?php endforeach ?>
				</ul>
				<?php else: ?>
				<div class="empty-property-block">
					<p>Add your URLs! <code class="pull-right">&lt;a rel=&quot;me&quot; class=&quot;u-url&quot;>…&lt;/a></code></p>
				</div>
				<?php endif ?>

				<p class="property-block-name">Email</p>
				<?php if (Mf2\hasProp($hCard, 'email')): ?>
				<ul>
					<?php foreach ($hCard['properties']['email'] as $email): ?>
					<li><a href="<?= $email ?>"><?= $email ?></a></li>
					<?php endforeach ?>
				</ul>
				<?php else: ?>
				<div class="empty-property-block">
					<p>Add your Email! <code class="pull-right">&lt;a rel=&quot;me&quot; class=&quot;u-email&quot;>…&lt;/a></code></p>
				</div>
				<?php endif ?>

				<p class="property-block-name">Note</p>
				<?php if (Mf2\hasProp($hCard, 'note')): ?>
				<p><?= Mf2\getProp($hCard, 'note') ?></p>
				<?php else: ?>
				<div class="empty-property-block">
					<p>Add a note/bio! <code class="pull-right">&lt;p class=&quot;p-note&quot;>…&lt/p></code></p>
				</div>
				<?php endif ?>

			</div>
			<?php endif ?>

			<?= $render('silo-hint.html', array('url' => $url)) ?>
		<?php endif ?>
	</div>
	<?php endif ?>
	
	<small>Want to be able to use h-card data in your code? Check out the open-source <a href="http://microformats.org/wiki/parsers">implementations</a>.</small>

	<?php if (empty($composite_view)): ?>
		<hr />
		<p> <a href="/validate-rel-me/">Previous Step</a> | <a href="/">Home</a> | <a href="/validate-h-entry/">Next Step</a> </p>
	<?php endif ?>

	</div><!--/.span-->
</div>
