<?php // phpcs:ignoreFile ?>
</div> <!-- .container -->

<!-- Load JS here as and when we need it -->
<script src="/js/jquery-1.8.3.min.js"></script>
<!--<script src="/js/jquery-ui-1.10.3.custom.min.js"></script>-->
<!--<script src="/js/jquery.ui.touch-punch.min.js"></script>-->
<!--<script src="/js/bootstrap.min.js"></script>-->
<!--<script src="/js/bootstrap-select.js"></script>-->
<!--<script src="/js/bootstrap-switch.js"></script>-->
<!--<script src="/js/flatui-checkbox.js"></script>-->
<!--<script src="/js/flatui-radio.js"></script>-->
<!--<script src="/js/jquery.tagsinput.js"></script>-->
<!--<script src="/js/jquery.placeholder.js"></script>-->
<!--<script src="/js/jquery.stacktable.js"></script>-->
<!--<script src="/js/underscore-min.js"></script>-->
<!--<script src="/js/application.js"></script>-->
<script>
	// rel-me UI
	(function($) {
		var results = $('.rel-me-result'),
			url = document.querySelector('.results-url').href,
			container = $('.result'),
			progress = $('<div class="progress"><div class="bar bar-success"></div><div class="bar bar-warning"></div><div class="bar bar-danger"></div></div>'),
			successBar = progress.children('.bar-success'),
			errorBar = progress.children('.bar-danger'),
			warningBar = progress.children('.bar-warning'),
			successBarWidth = 0,
			errorBarWidth = 0,
			warningBarWidth = 0;

		container.prepend(progress);

		results.each(function() {
			var relMeUrl = this.querySelector('a').href,
							el = $(this),
							spinner = $('<span class="spinner danger"> loading…</span>');

			el.append(spinner);

			var parts = relMeUrl.split('://');

			if (parts[0] != 'http' && parts[0] != 'https') {
				spinner.text(' only http and https links are validated ');
				successBarWidth += 100 / results.length;
				successBar.width(successBarWidth + '%');
			} else {
				$.getJSON('/rel-me-check/', {
					url1: url, url2: relMeUrl
				}).done( function(data) {
					console.log(data);
					spinner.text(' ' + data.response);

					if ( data.pass ) {
						successBarWidth += 100 / results.length;
						successBar.width(successBarWidth + '%');
					} else {
						warningBarWidth += 100 / results.length;
						warningBar.width(warningBarWidth + '%');
					}
				}).fail( function(xhr, textStatus, errorThrown) {
					spinner.text(' ' + data.response);
					errorBarWidth += 100 / results.length;
					errorBar.width(errorBarWidth + '%');
				});
			}
		});
	}($));
</script>
</body>
</html>
