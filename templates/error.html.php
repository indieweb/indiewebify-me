<div class="row demo-row">

	<h1>Something Went Wrong</h1>

	<p><?= $message ?></p>

	<? if ($tryAgain): ?>
	<div class="try-again">
		<?= $tryAgain ?>
	</div>
	<? endif ?>
</div>
