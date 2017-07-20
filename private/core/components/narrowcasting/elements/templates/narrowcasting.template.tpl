<!doctype html>
<html lang="[[++cultureKey:default=`nl`]]" class="no-js">
<head>
	<title>[[++site_name]] | [[*longtitle:default=`[[*pagetitle]]`]]</title>
	<base href="[[++site_url]]" />
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">
	<meta name="description" content="" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
	<meta name="robots" content="noindex, nofollow" />

	<link rel="stylesheet" href="/narrowcasting/assets/interface/css/main.css" />

	<link rel="shortcut icon" type="image/ico" href="/narrowcasting/assets/interface/images/favicon.png" />
	<link rel="apple-touch-icon" sizes="152x152" href="/narrowcasting/assets/interface/images/152x152.png" />
</head>

<body>
<div class="window window-[[!+narrowcasting.player.mode]] window-[[!+narrowcasting.player.resolution]]">
	<!-- Error message -->
	<div class="broadcast-error-message" data-template="error">
		<div class="broadcast-error-message-inner">
			<h2><span data-placeholder="title"></span></h2>
			<p data-placeholder="message"></p>
		</div>
	</div>

	<!-- Ticker -->
	<div class="ticker">
		<div class="ticker-inner" data-placeholder="ticker">
			<ul data-template="ticker">
				<li data-template="item" data-placeholder-class="source">
					    <span class="icons">
					        <img src="/narrowcasting/assets/interface/images/ticker-icon-omrop-fryslan.svg" class="omrop-fryslan" />
					    </span>
					<span data-placeholder="title"></span>
				</li>
			</ul>
		</div>
	</div>

	<div class="header">
		<!-- Logo -->
		<div class="logo">
			<img src="/narrowcasting/assets/interface/images/logo-text.svg" />
		</div>

		<!-- Clock -->
		<div class="clock">
			<div class="time" data-placeholder="time"></div>
			<div class="date" data-placeholder="date"></div>
		</div>
	</div>

	<!-- Splides -->
	<div class="slides">
		<div data-template="default">
			<div class="slide slide-default" data-placeholder-class="source">
				<div class="slide-inner">
					<div class="content">
						<div class="image">
							<img src="" data-placeholder="image" data-placeholder-wrapper="image" />
						</div>
						<h1 data-placeholder="title" data-placeholder-renders="striptags,ellipsis:150"></h1>
						<div data-placeholder="content" data-placeholder-renders="striptags:p|h2|h3|h4|strong|em|span|br"></div>
					</div>
				</div>
			</div>
		</div>
		<div data-template="media">
			<div class="slide slide-media">
				<div class="slide-inner">
					<div class="content">
						<iframe src="" data-placeholder="youtube" data-placeholder-renders="youtube" class="video" frameborder="0" allowfullscreen></iframe>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
    var player        = '[[!+narrowcasting.player.key]]',
        broadcast     = '[[!+narrowcasting.broadcast.id]]',
        broadcastFeed = '[[!+narrowcasting.broadcast.feed]]',
        preview       = [[!+narrowcasting.preview]];
</script>

<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
<script type="text/javascript" src="/narrowcasting/assets/interface/javascript/modenizer.js"></script>

<script type="text/javascript" src="/narrowcasting/assets/interface/javascript/narrowcasting.js"></script>

</body>
</html>