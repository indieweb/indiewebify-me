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
			
			<div class="preview-h-entry preview-block">
				
				<p class="property-block-name">Author</p>
				<? if (Mf2\hasProp($hEntry, 'author')): $author = Mf2\getProp($hEntry, 'author') ?>
				<div class="minicard p-author">
					<? if (Mf2\hasProp($author, 'photo')): ?>
					<img class="u-photo" src="<?= Mf2\getProp($author, 'photo')?>" alt="" />
					<? else: ?>
					<div class="empty-property-block photo-block">
						<p>Add a photo!</p>
						<p><code>&lt;img class=&quot;u-photo&quot; src=&quot;…&quot /></code></p>
					</div>
					<? endif ?>
					<span class="p-name"><?= Mf2\getProp($author, 'name') ?></span>
					<a href="<?= Mf2\getProp($author, 'url') ?>" rel="author" class="u-url"><?= Mf2\getProp($author, 'url') ?></a>
				</div>
				<? else: ?>
				<div class="empty-property-block">
					<p>Add an author! </p>
					<pre><code>&lt;a rel=&quot;author&quot; class=&quot;p-author h-card&quot; href=&quot;…&quot;>Your Name&lt;/a></code></pre>
				</div>
				<? endif ?>
				
				<p class="property-block-name">Content</p>
				<? if (Mf2\hasProp($hEntry, 'content')): ?>
				<div class="e-content"><?= Mf2\getProp($hEntry, 'content') ?></div>
				<? else: ?>
				<div class="empty-property-block">
					<p>Add some content! <code class="pull-right">&lt;p class=&quot;e-content&quot;>…</code></p>
				</div>
				<? endif ?>
				
				<p class="property-block-name">Published
				<? if (Mf2\hasProp($hEntry, 'published')): ?>
				<time class="dt-published"><?= Mf2\getProp($hEntry, 'published') ?></time></p>
				<? else: ?>
				</p><p class="empty-property-block">Add a publication datetime! <code class="pull-right">&lt;time class=&quot;dt-published&quot; datetime=&quot;YYYY-MM-DD HH:MM:SS&quot;>The Date&quot;/datetime></code></p>
				<? endif ?>
				
				<p class="property-block-name">URL
				<? if (Mf2\hasProp($hEntry, 'url')): ?>
				<a href="<?= Mf2\getProp($hEntry, 'url') ?>"><?= Mf2\getProp($hEntry, 'url') ?></a></p>
				<? else: ?>
				</p><p class="empty-property-block">Add a URL! <code class="pull-right">&lt;a class=&quot;u-url&quot; href=&quot;…&quot;>…&lt;/a></code></p>
				<? endif ?>
				
				<p class="property-block-name">Syndicated Copies</p>
				<? if (Mf2\hasProp($hEntry, 'syndication')): ?>
				<ul>
					<? foreach ($hEntry['properties']['syndication'] as $pSyndication): ?>
					<li><?= $pSyndication ?></li>
					<? endforeach ?>
				</ul>
				<? else: ?>
				</p><p class="empty-property-block">Add URLs of <a href="http://indiewebcamp.com/POSSE">POSSEd</a> copies! <code class="pull-right">&lt;a rel=&quot;syndication&quot; class=&quot;u-syndication&quot; href=&quot;…&quot;>…&lt;/a></code></p>
				<? endif ?>
				
				<p class="property-block-name">Categories</p>
				<? if (Mf2\hasProp($hEntry, 'category')): ?>
				<ul>
					<? foreach ($hEntry['properties']['category'] as $pCat): ?>
					<li><?= $pCat ?></li>
					<? endforeach ?>
				</ul>
				<? else: ?>
				</p><p class="empty-property-block">Add some categories! <code class="pull-right">&lt;a rel=&quot;tag&quot; class=&quot;p-category&quot; href=&quot;…&quot;>…&lt;/a></code></p>
				<? endif ?>
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
