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
	<div class="window">
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
    						<h1 data-placeholder="title" data-placeholder-renders="striptags,ellipsis:150"></h1>
    						<div data-placeholder="content" data-placeholder-renders="striptags,ellipsis:200"></div>
    					</div>
    					<div class="image">
    					    <img src="" data-placeholder="image" data-placeholder-wrapper="image" />
    					</div>
    				</div>
    			</div>
            </div>
			<!--<div data-template="default">
				<div class="slide slide-default slide-default-no-image" data-placeholder-class="source">
					<div class="slide-inner">
						<div class="content">
							<h1 data-placeholder="title" data-placeholder-renders="striptags,ellipsis:150"></h1>
							<div data-placeholder="content" data-placeholder-renders="striptags,ellipsis:400"></div>
						</div>
					</div>
				</div>
			</div>
            div data-template="media">
                <div class="slide slide-media">
    				<div class="slide-inner">
    					<h1 data-placeholder="title"></h1>
    					<img src="" data-placeholder="image" class="image" />
    					<iframe src="" data-placeholder="video" class="video" frameborder="0" allowfullscreen></iframe>
    				</div>
    			</div>
    		</div>
		    <div data-template="buienradar">
    			<div class="slide slide-buienradar">
    				<div class="slide-inner">
    					<div class="weather-content">
    						<div class="content">
    							<h1 data-placeholder="title"></h1>
    							<ul data-placeholder="forecast">
    								<li data-template="forecast">
    									<strong data-placeholder="date"></strong>
    									<span class="icon">
    									    <span data-placeholder="iconcode" data-placeholder-class="iconcode" class="iconcode"></span>
    									</span>
    									<span class="temprature">
    									    <span data-placeholder="mintemperature"></span>° / <span data-placeholder="maxtemperature"></span>°
    									</span>
    									<span class="wind">
    									    <span data-placeholder="winddirection"></span> <span data-placeholder="beaufort"></span>
    									</span>
    								</li>
    							</ul>
    							<div data-placeholder="precipitation"></div>
    						</div>
    					</div>
    					<div class="weather-radar">
    						<img src="" data-placeholder="radar" class="image" />
    					</div>
    				</div>
    			</div>
    		</div>
    		<div data-template="social-media">
        		<div class="slide slide-social-media">
    				<div class="slide-inner">
    					<div class="content">
    						<h1 data-placeholder="title"></h1>
    						<ul data-placeholder="social-media">
    							<li class="facebook" data-template="facebook">
    								<div class="block">
    									<span class="icon"></span>
    									<img src="" class="avatar" data-placeholder="image" />
    									<h1 data-placeholder="name"></h1>
    									<h2 data-placeholder="username"></h2>
    									<p data-placeholder="content"></p>
    								</div>
    							</li>
    							<li class="twitter" data-template="twitter">
    								<div class="block">
    									<span class="icon"></span>
    									<img src="" class="avatar" data-placeholder="image" />
    									<h1 data-placeholder="name"></h1>
    									<h2 data-placeholder="username"></h2>
    									<p data-placeholder="content"></p>
    								</div>
    							</li>
    							<li class="instagram" data-template="instagram">
    								<div class="block">
    									<span class="icon"></span>
    									<img src="" class="avatar" data-placeholder="image" />
    									<h1 data-placeholder="name"></h1>
    									<h2 data-placeholder="username"></h2>
    									<p><img src="" data-placeholder="media" class="media" /></p>
    								</div>
    							</li>
    						</ul>
    					</div>
    				</div>
    			</div>
    		</div>
    		<div data-template="sponsor">
    		    <div class="slide slide-sponsor">
    				<div class="slide-inner">
    					<div class="content">
    						<img src="" data-placeholder="logo" />
    					</div>
    				</div>
    			</div>
    		</div-->
    	</div>
	</div>
	
	<script type="text/javascript">
	    var player    = '[[!+narrowcasting.player]]',
	        broadcast = '[[!+narrowcasting.broadcast]]',
	        preview   = [[!+narrowcasting.preview]];
	</script>
	
	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
	<script type="text/javascript" src="/narrowcasting/assets/interface/javascript/modenizer.js"></script>
	
	<script type="text/javascript" src="/narrowcasting/assets/interface/javascript/narrowcasting.js"></script>
	<script type="text/javascript" src="/narrowcasting/assets/interface/javascript/slide.buienradar.js"></script>
    <script type="text/javascript" src="/narrowcasting/assets/interface/javascript/slide.social-media.js"></script>
    <script type="text/javascript" src="/narrowcasting/assets/interface/javascript/slide.sponsor.js"></script>
	
</body>
</html>