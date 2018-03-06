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

    <link rel="stylesheet" href="/digitalsignage/assets/interface/css/main.css?hash=[[!+digitalsignage.hash]]" />

    <link rel="shortcut icon" type="image/ico" href="/digitalsignage/assets/interface/images/favicon.png" />
    <link rel="apple-touch-icon" sizes="152x152" href="/digitalsignage/assets/interface/images/152x152.png" />
</head>

<body>
<div class="window window-[[!+digitalsignage.player.mode]] window-[[!+digitalsignage.player.resolution]]">
    <!-- Begin Error Reporting -->
    <div class="errors" data-placeholder="errors">
        <div class="error-message" data-template="error">
            <h2 data-placeholder="title"></h2>
            <p data-placeholder="message"></p>
        </div>
    </div>
    <!-- End Error Reporting -->
    <div class="header">
        <div class="logo">
            <img src="/digitalsignage/assets/interface/images/logo.svg" />
        </div>

        <!-- Begin Clock Plugin -->
        <div class="clock" data-plugin="ClockPlugin">
            <div class="time" data-placeholder="time" data-placeholder-renders="date:%H:%I uur"></div>
            <div class="date" data-placeholder="date" data-placeholder-renders="date:%d %F %Y"></div>
        </div>
        <!-- End Clock Plugin -->
    </div>

    <!-- Begin Newsticker Plugin -->
    <div class="ticker" data-plugin="TickerPlugin" data-plugin-settings="{'feed': '[[!+digitalsignage.broadcast.feed]]', 'feedType': 'JSON'}">
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
    <!-- End Newsticker Plugin -->

    <!-- Begin Slides -->
    <div class="slides" data-placeholder="slides">
        <div data-template="default">
            <!-- Begin Default Slide -->
            <div class="slide slide-default" data-placeholder-class="source">
                <div class="slide-inner">
                    <div class="image">
                        <img src="" data-placeholder="image" data-placeholder-wrapper="image" />
                    </div>
                    <div class="content">
                        <div class="content-mask">
                            <h1 data-placeholder="title" data-placeholder-renders="striptags,ellipsis:150"></h1>
                            <div data-placeholder="content" data-placeholder-renders="striptags:p|h2|h3|h4|strong|em|span|br|ul|ol|li|img"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Default Slide -->
        </div>
        <div data-template="media">
            <!-- Begin Media Slide -->
            <div class="slide slide-media">
                <div class="slide-inner">
                    <div class="content">
                        <img src="" data-placeholder="image" data-placeholder-wrapper="image" />
                        <iframe src="" data-placeholder="youtube" data-placeholder-renders="youtube" class="video" frameborder="0" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
            <!-- End Media Slide -->
        </div>
        <div data-template="payoff">
            <!-- Begin Payoff Slide -->
            <div class="slide slide-payoff" data-placeholder-class="color">
                <div class="slide-inner">
                    <div class="logo">
                        <img src="/digitalsignage/assets/interface/images/logo.svg" />
                    </div>
                    <div class="content">
                        <div data-placeholder="content" data-placeholder-renders="striptags:p|strong|em|span|br"></div>
                    </div>
                </div>
            </div>
            <!-- End Payoff Slide -->
        </div>
        <div data-template="feed" data-plugin-settings="{'feed': '/ds/feed-export.json', 'feedType': 'JSON'}">
            <!-- Begin Feed Slide -->
            <div class="slide slide-feed">
                <div class="slide-inner">
                    <div class="items" data-placeholder="items">
                        <div class="item"  data-template="item" data-placeholder-class="source">
                            <div class="image">
                                <img src="" data-placeholder="image" data-placeholder-wrapper="image" />
                            </div>
                            <div class="content">
                                <div class="content-mask">
                                    <h2 data-placeholder="title" data-placeholder-renders="striptags,ellipsis:150"></h2>
                                    <p data-placeholder="content" data-placeholder-renders="striptags:p,ellipsis:150"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Feed Slide -->
        </div>
        <div data-template="buienradar">
            <!-- Begin Buienradar Slide -->
            <div class="slide slide-buienradar">
                <div class="slide-inner">
                    <div class="image">
                        <img src="" data-placeholder="radar" data-placeholder-wrapper="image" />
                    </div>
                    <div class="content">
                        <h1 data-placeholder="title"></h1>
                        <ul class="forecasts" data-placeholder="forecasts">
                            <li data-template="forecast">
                                <div class="forecast-inner">
                                    <h2 data-placeholder="date" data-placeholder-renders="date:%q"></h2>
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
                                            <img src="/digitalsignage/assets/interface/images/buienradar/precipation.svg" /><span data-placeholder="precipationmm"></span> mm
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
            <!-- End Buienradar Slide -->
        </div>
    </div>
    <!-- End Slides -->
</div>

<!-- DigitalSignage Settings -->
<script type="text/javascript">
    var settings = {
        'debug'     : true,
        'player'    : '[[!+digitalsignage.player.key]]',
        'broadcast' : {
            'id'        : '[[!+digitalsignage.broadcast.id]]',
            'feed'      : '[[!+digitalsignage.broadcast.feed]]'
        },
        'callback'  : '[[!+digitalsignage.callback.feed]]',
        'preview'   : [[!+digitalsignage.preview]]
    };
</script>

<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>

<script type="text/javascript" src="/digitalsignage/assets/interface/javascript/digitalsignage.js?hash=[[!+digitalsignage.hash]]"></script>

<script type="text/javascript" src="/digitalsignage/assets/interface/javascript/feed.slide.js?hash=[[!+digitalsignage.hash]]"></script>
<script type="text/javascript" src="/digitalsignage/assets/interface/javascript/buienradar.slide.js?hash=[[!+digitalsignage.hash]]"></script>

</body>
</html>