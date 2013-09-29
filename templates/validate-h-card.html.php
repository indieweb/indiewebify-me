<? use BarnabyWalters\Mf2 ?>
<div class="row demo-row">
	<h1><span class="fui-new"></span> Publishing on the IndieWeb <small>Level 2</small></h1>

	<h2>1. Markup your content (Posts, Articles, Notes, etc...) with <a href="http://microformats.org/" target="_blank">microformats2</a></h2>
	<p>&nbsp;</p>

	<h4>Validate your <strong>homepage</strong> has an <a href="http://microformats.org/wiki/h-card" target="_blank">h-card</a>:</h4>
	
	<div class="result">
		<? if ($error): ?>
		<h4>Something Went Wrong!</strong></h4>
		<p>When fetching <code><?= $url ?></code>, we got this problem:</p>
		<p><?= $error['message'] ?></p>
		<? elseif ($hCard): ?>
		<h4>Success!</h4>
		
		<p>We found the following <code>h-card</code> on your site:</p>
		
		<div class="preview-h-card">
			<? if (Mf2\hasProp($hCard, 'photo')): ?>
			<img class="u-photo" src="<?= Mf2\getProp($hCard, 'photo')?>" alt="" />
			<? endif ?>
			<p class="p-name"><?= Mf2\getProp($hCard, 'name') ?></p>
			<!-- TODO: add more properties here -->
		</div>
		<? endif ?>
	</div>
	
	<form class="row" action="/validate-h-card/" method="get">
		<div class="span4">
			<input type="text" id="validate-h-card-url" name="url" value="<?= $url ?>" placeholder="http://yoursite.com" class="span4" />
		</div>
		<div class="span3">
			<button type="submit" id="validate-h-card" class="btn btn-large btn-block btn-primary">Validate h-card</button>
		</div>
	</form>
</div>