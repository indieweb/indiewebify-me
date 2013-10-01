<? use BarnabyWalters\Mf2 ?>

<div class="row demo-row">
	<h4>Make sure your <strong>posts/notes</strong> are marked up with <a href="http://microformats.org/wiki/h-entry" target="_blank">h-entry</a>:</h4>
	
	<? if ($error or $hEntry): ?>
	<div class="result alert <? if ($error): ?>alert-warning<? else: ?>alert-success<? endif ?>">
		<? if ($error): ?>
			<h4>Something Went Wrong!</strong></h4>
			<p>When fetching <code><?= $url ?></code>, we got this problem:</p>
			<p><?= $error['message'] ?></p>
		<? elseif ($hEntry): ?>
			<h4>Success!</h4>

			<p>We found the following <code>h-entry</code> on your site:</p>
			
			<div class="preview-h-entry">
					<? if (Mf2\hasProp($hEntry, 'author')): $author = Mf2\getProp($hEntry, 'author') ?>
					<div class="minicard p-author">
						<? if (Mf2\hasProp($author, 'photo')): ?>
						<img class="u-photo" src="<?= Mf2\getProp($author, 'photo')?>" alt="" />
						<? endif ?>
						<span class="p-name"><?= Mf2\getProp($author, 'name') ?></span>
						<a href="<?= Mf2\getProp($author, 'url') ?>" rel="author" class="u-url"><?= Mf2\getProp($author, 'url') ?></a>
					</div>
					<? else: ?>
					<div class="info empty">
						<p><strong>Your h-entry has no <a href="http://indiewebcamp.com/authorship">authorship</a> information</strong> — you should consider adding some.</p>
					</div>
					<? endif ?>
					<? if (Mf2\hasProp($hEntry, 'content')): ?>
					<div class="e-content"><?= Mf2\getProp($hEntry, 'content') ?></div>
					<? else: ?>
					<div class="info empty">
						<p><strong>Your h-entry has no content</strong> — you should consider adding some, otherwise it’s not much of a post!</p>
						
						<p>Make sure it’s marked up with <code>class="e-content"</code>.</p>
					</div>
					<? endif ?>
					
					<div class="meta">
						<? if (Mf2\hasProp($hEntry, 'published')): ?>
						<p><time class="dt-published"><?= Mf2\getProp($hEntry, 'published') ?></time></p>
						<? else: ?>
						<p class="info empty"><strong>Your h-entry has no published date</strong> — you should add one.</p>
						<? endif ?>
					</div>
			</div>
		<? endif ?>
	</div>
	<? endif ?>

	<form class="row" action="/validate-h-entry/" method="get">
		<div class="span4">
			<input type="text" id="validate-h-entry-url" name="url" value="<?= $url ?>" placeholder="http://yoursite.com/notes/123456" class="span4" />
		</div>
		<div class="span3">
			<button type="submit" id="validate-h-entry" class="btn btn-large btn-block btn-primary">Validate h-entry</button>
		</div>
	</form>
</div>
