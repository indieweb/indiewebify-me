<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>IndieWebify.Me - a guide to getting you on the IndieWeb</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Loading Bootstrap -->
    <link href="/bootstrap/css/bootstrap.css" rel="stylesheet">

    <!-- Loading Flat UI -->
    <link href="/css/flat-ui.css" rel="stylesheet">

    <link rel="shortcut icon" href="/images/favicon.ico">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
    <!--[if lt IE 9]>
      <script src="js/html5shiv.js"></script>
    <![endif]-->
   
		<style>
		/* CSS here temporarily while we figure out how to structure it */
		
		* {
			max-width: 100%;
		}
		
		.empty-property-block {
			background: #555;
			color: #efefef;
			text-shadow: none;
			border: #334 1px solid;
			box-shadow: #333 0 0 1em inset;
			padding: 0.5em;
		}
		
		.property-block-name {
			margin-bottom: 0.1em;
			margin-top: 1em;
			font-variant: small-caps;
			color: #555;
			font-size: 0.8em;
			clear: both;
		}
		
		.preview-block {
			background: #efefef;
			padding: 1em;
			max-width: 30em;
			border: 2px solid #DDD;
		}
		
		/* h-card validation */
		.preview-h-card .photo-block {
			max-width: 13em;
			max-height: 7em;
			float: left;
			margin: 0 0.5em 0.5em 0;
		}
		
		.preview-h-card .p-name {
			float: left;
			font-size: 2em;
		}
		
		/* h-entry validation */
		.preview-h-entry .p-author .u-photo {
			max-height: 2em;
		}
		
		.preview-h-entry .e-content {
			border-left: 0.5em #ddd solid;
			padding: 0.1em 0.5em;
			font-size: 0.9em;
			color: #333;
			background: #fff;
		}

		body {
			padding: 2em;
		}
		</style>
		
  </head>
  <body>
    <div class="container">