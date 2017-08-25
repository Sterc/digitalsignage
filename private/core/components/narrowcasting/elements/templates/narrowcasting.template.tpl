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
        
        <div class="header">
			<!-- Logo -->
			<div class="logo">
				<img src="/narrowcasting/assets/interface/images/logo-text.svg" />
			</div>

			<!-- Clock -->
			<div class="clock" data-plugin="Clock" data-plugin-settings="{'formatTime': '%H:%I uur', 'formatDate': '%d %F %Y'}">
				<div class="date" data-placeholder="date"></div>
				<div class="time" data-placeholder="time"></div>
			</div>
			
			<!-- Social Media -->
            <div class="social-media" data-plugin="SocialMediaWidget" data-plugin-settings="{'feed': '/narrowcasting/socialmediawidget.json', 'feedType': 'JSON'}">
                <div class="social-media-inner" data-placeholder="social-media">
                    <div class="social-media-item" data-template="item">
                        <div class="image">
                            <img src="" data-placeholder="image" data-placeholder-wrapper="image" />
                        </div>
                        <div class="content">
                            <div data-placeholder="content" data-placeholder-renders="striptags,ellipsis:100"></div>
                        </div>
                    </div>
                </div>
            </div>
		</div>

        <!-- Ticker -->
		<div class="ticker" data-plugin="Newsticker" data-plugin-settings="{'feed': '[[!+narrowcasting.broadcast.feed]]', 'feedType': 'JSON'}">
			<div class="ticker-inner" data-placeholder="ticker">
				<ul data-template="ticker">
					<li data-template="item" data-placeholder-class="source">
					    <span class="icons">
					        <span class="circle"></span>
					    </span>
					    <span data-placeholder="title"></span>
					</li>
				</ul>
			</div>
		</div>

		<!-- Slides -->
		<div class="slides" data-placeholder="slides">
		    <div data-template="default">
    			<div class="slide slide-default" data-placeholder-class="source">
    				<div class="slide-inner">
					    <div class="image">
                            <img src="" data-placeholder="image" data-placeholder-wrapper="image" />
                        </div>
                        <div class="content">
                            <div class="content-mask">
        					    <h1 data-placeholder="title" data-placeholder-renders="striptags,ellipsis:150"></h1>
        					    <div data-placeholder="content" data-placeholder-renders="striptags:p|h2|h3|h4|strong|em|span|br|ul|ol|li"></div>
    					    </div>
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
            <div data-template="buienradar">
                <div class="slide slide-buienradar">
                    <video class="background-video" autoplay>
                      <source src="/narrowcasting/assets/interface/images/buienradar/buienradar.mp4" type="video/mp4">
                    </video>
                    <div class="slide-inner">
                        <div class="image">
                            <img src="" data-placeholder="radar" data-placeholder-wrapper="image" />
                        </div>
                        <div class="content">
                            <ul class="forecasts" data-placeholder="forecasts">
                                <li data-template="forecast">
                                    <div class="forecast-inner">
                                        <h2 data-placeholder="date"></h2>
                                        <div class="icon">
                                            <img src="" data-placeholder="weatherIcon" />
                                        </div>
                                        <div class="data">
                                            <div class="temperature">
                                                <span class="temperature-min">
                                                    <span data-placeholder="mintemperature" data-placeholder="mintemperature"></span>°
                                                </span>
                                                /
                                                <span class="temperature-max">
                                                    <span data-placeholder="maxtemperature" data-placeholder="maxtemperature"></span>°
                                                </span>
                                            </div>
                                            <div class="rain">
                                                <img src="/narrowcasting/assets/interface/images/buienradar/precipation.svg" /><span data-placeholder="precipationmm"></span> mm
                                            </div>
                                            <div class="wind">
                                                <img src="" data-placeholder="windIcon" /><span data-placeholder="winddirection"></span> <span data-placeholder="beaufort"></span>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
    	</div>
	</div>

	<script type="text/javascript">
    	var narrowcasting = {
            'player'    : '[[!+narrowcasting.player.key]]',
            'broadcast' : {
                'id'        : '[[!+narrowcasting.broadcast.id]]',
                'feed'      : '[[!+narrowcasting.broadcast.feed]]'
            },
            'callback'  : '[[!+narrowcasting.callback.feed]]',
            'preview'   : [[!+narrowcasting.preview]]
        }
	</script>

	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
	<script type="text/javascript" src="/narrowcasting/assets/interface/javascript/modenizer.js"></script>

    <script type="text/javascript" src="/narrowcasting/assets/interface/javascript/socialmedia.widget.js"></script>
    <script type="text/javascript" src="/narrowcasting/assets/interface/javascript/buienradar.slide.js"></script>
	<script type="text/javascript" src="/narrowcasting/assets/interface/javascript/narrowcasting.js"></script>

</body>
</html>
