<?php namespace Indieweb\IndiewebifyMe; use BarnabyWalters\Mf2; ?>

<div class="row demo-row">
	<h4>Make sure your <strong>posts/notes</strong> are marked up with <a href="http://microformats.org/wiki/h-entry" target="_blank">h-entry</a>:</h4>
	
	<?php if ($error or $hEntry): ?>
	<div class="result alert <?php if ($error): ?>alert-warning<?php else: ?>alert-success<?php endif ?>">
		<?php if ($error): ?>
			<h4>Something Went Wrong!</strong></h4>
			<p>When fetching <code><?= $url ?></code>, we got this problem:</p>
			<p><?= $error['message'] ?></p>
		<?php elseif ($hEntry): ?>
			<h4>Success!</h4>

			<p>We found the following <code>h-entry</code> on your site:</p>
			
			<div class="preview-h-entry preview-block">
				
				<?php if (hEntryName($hEntry)): ?>
				<p class="property-block-name">Name</p>
				<p class="p-name"><?= hEntryName($hEntry) ?></p>
				<?php endif ?>
				
				<p class="property-block-name">Author</p>
				<?php if (Mf2\hasProp($hEntry, 'author')): $author = $hEntry['properties']['author'][0]; ?>
				<?php if (Mf2\isMicroformat($author)): ?>
				<div class="minicard p-author">
					<?php if (Mf2\hasProp($author, 'photo')): ?>
					<img class="u-photo" src="<?= Mf2\getProp($author, 'photo')?>" alt="" />
					<?php else: ?>
					<div class="empty-property-block photo-block">
						<p>Add a photo!</p>
						<p><code>&lt;img class=&quot;u-photo&quot; src=&quot;…&quot /></code></p>
					</div>
					<?php endif ?>
					<span class="p-name"><?= Mf2\getProp($author, 'name') ?></span>
					<a href="<?= Mf2\getProp($author, 'url') ?>" rel="author" class="u-url"><?= Mf2\getProp($author, 'url') ?></a>
				</div>
				<?php elseif (is_string($author)): ?>
				<div class="empty-property-block">
					<p>You’re marking up your post’s author as a string — add <code>h-card</code> to make it a full h-card!</p>
					<!-- TODO: use actual code snippet from site with h-card added -->
					<pre><code>&lt;a class=&quot;p-author <strong>h-card</strong>&quot; href=&quot;your-url.com&quot;>Your Name&lt/a></code></pre>
				</div>
				<?php endif ?>
				<?php else: ?>
				<div class="empty-property-block">
					<p>Add an author! </p>
					<pre><code>&lt;a rel=&quot;author&quot; class=&quot;p-author h-card&quot; href=&quot;…&quot;>Your Name&lt;/a></code></pre>
				</div>
				<?php endif ?>
				
				<p class="property-block-name">Content</p>
				<?php if (Mf2\hasProp($hEntry, 'content')): ?>
				<div class="e-content"><?= Mf2\getProp($hEntry, 'content') ?></div>
				<?php else: ?>
				<div class="empty-property-block">
					<p>Add some content! <code class="pull-right">&lt;p class=&quot;e-content&quot;>…</code></p>
				</div>
				<?php endif ?>
				
				<p class="property-block-name">Published
				<?php if (Mf2\hasProp($hEntry, 'published')): ?>
				<time class="dt-published"><?= Mf2\getProp($hEntry, 'published') ?></time></p>
				<?php else: ?>
				</p>
				<div class="empty-property-block">
					<p>Add a publication datetime!</p>
					<p><code>&lt;time class=&quot;dt-published&quot; datetime=&quot;YYYY-MM-DD HH:MM:SS&quot;>The Date&lt;/datetime></code></p>
				</div>
				<?php endif ?>
				
				<p class="property-block-name">URL
				<?php if (Mf2\hasProp($hEntry, 'url')): ?>
				<a href="<?= Mf2\getProp($hEntry, 'url') ?>"><?= Mf2\getProp($hEntry, 'url') ?></a></p>
				<?php else: ?>
				</p><p class="empty-property-block">Add a URL! <code class="pull-right">&lt;a class=&quot;u-url&quot; href=&quot;…&quot;>…&lt;/a></code></p>
				<?php endif ?>
				
				<p class="property-block-name">Syndicated Copies</p>
				<?php if (Mf2\hasProp($hEntry, 'syndication')): ?>
				<ul>
					<?php foreach ($hEntry['properties']['syndication'] as $pSyndication): ?>
					<li><?= $pSyndication ?></li>
					<?php endforeach ?>
				</ul>
				<?php else: ?>
				<div class="empty-property-block">
					<p>Add URLs of <a href="http://indiewebcamp.com/POSSE">POSSEd</a> copies!</p>
					<p><code>&lt;a rel=&quot;syndication&quot; class=&quot;u-syndication&quot; href=&quot;…&quot;>…&lt;/a></code></p>
				</div>
				<?php endif ?>
				
				<p class="property-block-name">Categories</p>
				<?php if (Mf2\hasProp($hEntry, 'category')): ?>
				<ul>
					<?php foreach ($hEntry['properties']['category'] as $pCat): ?>
					<li><?= $pCat ?></li>
					<?php endforeach ?>
				</ul>
				<?php else: ?>
				<p class="empty-property-block">Add some categories! <code class="pull-right">&lt;a class=&quot;p-category&quot; href=&quot;…&quot;>…&lt;/a></code></p>
				<?php endif ?>
			</div>
		<?php endif ?>
		
		<?= $render('silo-hint.html', array('url' => $url)) ?>
	</div>
	<?php endif ?>

	<form class="row" action="/validate-h-entry/" method="get">
		<div class="span4">
			<input type="text" id="validate-h-entry-url" name="url" value="<?= $url ?>" placeholder="http://yoursite.com/notes/123456" class="span4" />
		</div>
		<div class="span3">
			<button type="submit" id="validate-h-entry" class="btn btn-large btn-block btn-primary">Validate h-entry</button>
		</div>
	</form>
</div>
