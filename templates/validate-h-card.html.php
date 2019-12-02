<?php
	use BarnabyWalters\Mf2;

	$optional_properties = [
		'honorific-prefix' => 'Honorific prefix',
		'given-name' => 'Given (often first) name',
		'additional-name' => 'Other/middle name',
		'family-name' => 'Family (often last) name',
		'sort-string' => 'String to sort by',
		'honorific-suffix' => 'Honorific suffix',
		'nickname' => 'Nickname',
		'email' => 'Email address',
		'logo' => 'Logo',
		'uid' => 'Unique identifier',
		'category' => 'Category/tag',
		'adr' => 'Postal Address',
		'post-office-box' => 'Post Office Box',
		'street-address' => 'Street number and name',
		'extended-address' => 'Extended address',
		'locality' => 'City/town/village',
		'region' => 'State/province/county',
		'postal-code' => 'Postal code',
		'country-name' => 'Country',
		'label' => 'Label',
		'geo' => 'Geo',
		'latitude' => 'Latitude',
		'longitude' => 'Longitude',
		'altitude' => 'Altitude',
		'tel' => 'Telephone',
		'bday' => 'Birth Date',
		'key' => 'Cryptographic public key',
		'org' => 'Organization',
		'job-title' => 'Job title',
		'role' => 'Description of role',
		'impp' => 'Instant Messaging and Presence Protocol',
		'sex' => 'Biological sex',
		'gender-identity' => 'Gender identity',
		'anniversary' => 'Anniversary',
	];

?>
<div class="row demo-row">
	<div class="span12">

	<h1><span class="fui-new"></span> Publishing on the IndieWeb <small>Level 2</small></h1>

	<h2>1. Mark up your content (Profile, Notes, Articles, etc…) with <a href="http://microformats.org/" target="_blank">microformats2</a></h2>

	<p>Other humans can already understand your profile information and the things you post on your site. By adding a few simple class names to your HTML, other people’s software can understand it and use it for things like <a href="https://indieweb.org/reply-context">reply contexts</a>, <a href="https://indieweb.org/comment">cross-site comments</a>, <a href="https://indieweb.org/rsvp">event RSVPs</a>, and more.</p>

	<h3>Check your <b>homepage <a href="http://microformats.org/wiki/h-card" target="_blank">h-card</a></b>:</h3>

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
				<p>An h-card was found on your site, but it’s not marked up as the <a href="http://microformats.org/wiki/representative-h-card-parsing">representative h-card</a>!</p>
				<p> To identify the h-card that <em>represents</em> the page, you can: </p>
				<ul>
					<li> Add <code>class=&quot;u-url u-uid&quot;</code> on the h-card's link to <?=htmlspecialchars($url);?> </li>
					<li> <b>Or:</b> add <code>class=&quot;u-url&quot; rel=&quot;me&quot;</code> on the h-card's link to <?=htmlspecialchars($url);?> </li>
				</ul>
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

				<?php if (Mf2\hasProp($hCard, 'email')): ?>
				<p class="property-block-name">Email</p>
				<ul>
					<?php foreach ($hCard['properties']['email'] as $email): ?>
					<li><a href="<?= $email ?>"><?= $email ?></a></li>
					<?php endforeach ?>
				</ul>
				<?php endif ?>

				<?php if (Mf2\hasProp($hCard, 'note')): ?>
				<p class="property-block-name">Note</p>
				<p><?= Mf2\getProp($hCard, 'note') ?></p>
				<?php else: ?>
				<div class="empty-property-block">
					<p>Got a brief bio like a Twitter/Instagram bio? Add it to your own h-card as a note property! <code class="pull-right">&lt;p class=&quot;p-note&quot;>…&lt/p></code></p>
				</div>
				<?php endif ?>

				<?php
				foreach ($optional_properties as $name => $label) {
					if (Mf2\hasProp($hCard, $name)) {
						echo sprintf('<p class="property-block-name">%s</p> <ul>', $label);
						foreach (Mf2\getPlaintextArray($hCard, $name) as $value) {
							echo sprintf('<li>%s</li>', $value);
						}
						echo '</ul>';
					}
				}
				?>

				<p> <a href="http://microformats.org/wiki/h-card#Properties">See the full list of h-card properties</a>. </p>

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
