		<!-- Templates -->
		<script type="text/template" id="template-validate-rel">		
			<h3>Success, here is the rel="me" data we found</h3> 
			<ul>
				<%= rels %>
			</ul>
			<p>You can now sign in to <a href="http://indiewebcamp.com/wiki/index.php?title=Special:UserLogin&returnto=Main_Page&returntoquery=local%3Dtrue" target="_blank">indiewebcamp.com</a> while signed into any of the above sites</p>
		</script>

		<script type="text/template" id="template-validate-h-card">
			<h3>Success, here is the hCard data we found</h3>
			<p>
				<strong>hCard</strong><br><br>
				<%= name %><br>
				<img src="<%= photo %>"><br>
				<a href="<%= url %>" target="_blank"><%= url %></a><br>
			</p>
		</script>
		

		<script type="text/template" id="template-validate-h-entry">
			<h3>Success, here is the hEntry data we found</h3>
			<p>
				<strong>hEntry</strong><br><br>
				<%= name %><br>
			</p>	
			<p>
				<%= published %><br>
				<%= updated %>
			</p>
			<p>
				<%= author %><br>
				<%= category %><br>
				<%= content %><br>
				<%= name %><br>
				<%= summary %><br>
				<%= syndication %><br>
				<%= updated %><br>
				<%= url %>
			</p>
		</script>
    
    <!-- Load JS here for greater good =============================-->
    <script src="js/jquery-1.8.3.min.js"></script>
    <script src="js/jquery-ui-1.10.3.custom.min.js"></script>
    <script src="js/jquery.ui.touch-punch.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/bootstrap-select.js"></script>
    <script src="js/bootstrap-switch.js"></script>
    <script src="js/flatui-checkbox.js"></script>
    <script src="js/flatui-radio.js"></script>
    <script src="js/jquery.tagsinput.js"></script>
    <script src="js/jquery.placeholder.js"></script>
    <script src="js/jquery.stacktable.js"></script>
    <script src="js/underscore-min.js"></script>
    <script src="js/application.js"></script>
    
    <script type="text/javascript">

		var validateMicroRel = function(result) {

			var da_rels = result.rels.me;

			var rel_urls = '';
			$.each(da_rels, function(key, url) {
				rel_urls += '<li><a href="' + url + '" target="_blank">' + url + '</a></li>';
			});

			$('#validate-rel-result').html(_.template($('#template-validate-rel').html(), {rels: rel_urls})).fadeIn('slow');
		}

		var validateMicroHCard = function(hcard) {	
			$('#validate-hcard-result').html(_.template($('#template-validate-hcard').html(), hcard)).fadeIn('slow');
		}

		var validateMicroHEntry = function(hentry) {
		
			var hentry_values = {};
			
			if (hentry.published != undefined) {
				hentry_values.published = 'Published ' + hentry.published;
			}
			else {
				hentry_values.published = 'No class named .published found';
			}
		
			$('#validate-hentry-result').html(_.template($('#template-validate-hentry').html(), hentry)).fadeIn('slow');
		};

		var fetchMicroformats = function (url, success, error) {
			$.ajax({
					url: 'microformat.php',
					type: 'POST',
					data: {url: url},
					dataType: 'json',
			  	success: success,
					error: error
				});
		};
		
	  $(document).ready(function(){
			// Bind form submit handlers
			$('form.validate-rel').submit(function (e) {
				e.preventDefault();
				var url = $(this).find('#validate-rel-url').val();
				console.log(url);
				// TODO: handle empty value
				fetchMicroformats(url, validateMicroRel, function (result) {
					console.log('error:', result);
				});
			});
			
			$('form.validate-h-card').submit(function (e) {
				e.preventDefault();
				var url = $(this).find('#validate-h-card-url').val();
				fetchMicroformats(url, validateMicroHCard, function (result) {
					console.log('error:', result);
				});
			});
			
			$('form.validate-h-entry').submit(function (e) {
				e.preventDefault();
				var url = $(this).find('#validate-h-entry-url').val();
				fetchMicroformats(url, validateMicroHEntry, function (result) {
					console.log('error:', result);
				});
			});
			
    });
    </script>
    
  </body>
</html>
