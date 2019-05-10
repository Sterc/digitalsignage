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
        <!-- Begin Clock Plugin -->
        <div class="clock" data-plugin="ClockPlugin">
            <div class="date" data-placeholder="date" data-placeholder-renders="date:%d %F %Y"></div>
            <div class="time" data-placeholder="time" data-placeholder-renders="date:%H:%I uur"></div>
        </div>
        <!-- End Clock Plugin -->

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

        <div class="logo">
            <img src="/digitalsignage/assets/interface/images/logo.svg" />
        </div>
    </div>

    <!-- Begin Slides -->
    <div class="slides" data-placeholder="slides">
        <div data-template="default">
            <!-- Begin Default Slide -->
            <div class="slide slide-default" data-placeholder-class="source">
                <div class="slide-inner">
                    <div class="image">
                        <div class="image-mask">
                            <img src="" data-placeholder="image" data-placeholder-wrapper="image" />
                        </div>
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
                        <h1 data-placeholder="title" data-placeholder-renders="striptags,ellipsis:150"></h1>
                        <img src="" data-placeholder="image" data-placeholder-wrapper="image" />
                        <iframe src="" data-placeholder="video_extern" data-placeholder-renders="video" class="video" frameborder="0" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
            <!-- End Media Slide -->
        </div>
        <div data-template="payoff">
            <!-- Begin Payoff Slide -->
            <div class="slide slide-payoff">
                <div class="slide-inner">
                    <div class="content">
                        <h1 data-placeholder="content" data-placeholder-renders="striptags" data-placeholder-class="size"></h1>
                    </div>
                </div>
            </div>
            <!-- End Payoff Slide -->
        </div>
        <div data-template="feed" data-plugin-settings="{'feed': '[[!+digitalsignage.feed.feed]]', 'feedType': 'JSON'}">
            <!-- Begin Feed Slide -->
            <div class="slide slide-feed">
                <div class="slide-inner">
                    <div class="items" data-placeholder="items">
                        <div class="item" data-template="item" data-placeholder-class="source">
                            <div class="image">
                                <div class="image-mask">
                                    <img src="" data-placeholder="image" data-placeholder-wrapper="image" />
                                </div>
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
                        <h1 data-placeholder="title" data-placeholder-renders="striptags,ellipsis:150"></h1>
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
                                                <span data-placeholder="mintemperature" data-placeholder="mintemperature"></span>°c
                                            </span>
                                            <span>/</span>
                                            <span class="temperature-max">
                                                <span data-placeholder="maxtemperature" data-placeholder="maxtemperature"></span>°c
                                            </span>
                                        </div>
                                    </div>
                                    <div class="data">
                                        <div class="rain">
                                            <img src="/digitalsignage/assets/interface/images/slide-buienradar/precipation.svg" /><span data-placeholder="precipitationmm"></span> <span>mm</span>
                                        </div>
                                        <div class="wind">
                                            <img src="" data-placeholder="windIcon" /><span data-placeholder="winddirection"></span> <span data-placeholder="beaufort"></span>
                                        </div>
                                    </div>
                                    <div class="data">
                                        <div class="sunrise">
                                            <img src="/digitalsignage/assets/interface/images/slide-buienradar/sunrise.svg" /><span data-placeholder="sunrise" data-placeholder-renders="date:%H:%i"></span>
                                        </div>
                                        <div class="sunset">
                                            <img src="/digitalsignage/assets/interface/images/slide-buienradar/sunset.svg" /><span data-placeholder="sunset" data-placeholder-renders="date:%H:%i"></span>
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
        <div data-template="countdown">
            <!-- Begin Countdown Slide -->
            <div class="slide slide-countdown">
                <div class="slide-inner">
                    <div class="content">
                        <h1 data-placeholder="title" data-placeholder-renders="striptags,ellipsis:150"></h1>
                        <div data-placeholder="content" data-placeholder-renders="striptags:p|h2|h3|h4|strong|em|span|br|ul|ol|li|img"></div>
                        <div class="countdown-container" data-date="tilldate"></div>
                    </div>
                </div>
            </div>
            <!-- End Countdown Slide -->
        </div>
        <div data-template="clock">
            <!-- Begin Clock Slide -->
            <div class="slide slide-clock">
                <div class="slide-inner">
                    <div class="clock-analog">
                        <div class="clock-analog--hour"></div>
                        <div class="clock-analog--minute"></div>
                        <div class="clock-analog--second"></div>
                        <div class="clock-analog--center"></div>
                    </div>
                </div>
            </div>
            <!-- End Clock Slide -->
        </div>
        <div data-template="dumpert">
            <!-- Begin Dumpert Slide -->
            <div class="slide slide-dumpert">
                <div class="slide-inner">
                    <div class="content">
                        <div data-placeholder="videos">
                            <div data-template="video">
                                <video class="video" muted autoplay>
                                    <source src="" data-placeholder="media.uri" data-placeholder-renders="video" />
                                </video>
                                <h1 data-placeholder="title"></h1>
                                <p class="date" data-placeholder="date" data-placeholder-renders="date:%d %F %Y %H:%i"></p>
                                <p data-placeholder="description"></p>
                            </div>
                        </div>
                    </div>
                    <div class="top-5">
                        <h2>TOP 5</h2>
                        <ul data-placeholder="top5s">
                            <li data-template="top5">
                                <img data-placeholder="thumbnail" />
                                <h3 data-placeholder="title"></h3>
                                <div data-placeholder="date" data-placeholder-renders="date:%d %F %Y %H:%i"></div>
                                <div>views: <span data-placeholder="stats.views_total"></span> kudos: <span data-placeholder="stats.kudos_total"></span></div>
                                <div class="rank">#<span data-placeholder="rank"></span></div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- End Dumpert Slide -->
        </div>
    </div>
    <!-- End Slides -->
</div>

<!-- Digital Signage Settings -->
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

<!-- Digital Signage -->
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
<script type="text/javascript" src="/digitalsignage/assets/interface/javascript/digitalsignage.js?hash=[[!+digitalsignage.hash]]"></script>

<!-- Feed slide -->
<script type="text/javascript" src="/digitalsignage/assets/interface/javascript/slides/feed.slide.js?hash=[[!+digitalsignage.hash]]"></script>

<!-- Buienradar Slide -->
<script type="text/javascript" src="/digitalsignage/assets/interface/javascript/slides/buienradar.slide.js?hash=[[!+digitalsignage.hash]]"></script>

<!-- Countdown Slide -->
<script type="text/javascript" src="/digitalsignage/assets/interface/libs/lodash.js/2.4.1/lodash.min.js"></script>
<script type="text/javascript" src="/digitalsignage/assets/interface/libs/jquery-countdown/jquery.countdown.min.js"></script>
<script type="text/javascript" src="/digitalsignage/assets/interface/javascript/slides/countdown.slide.js?hash=[[!+digitalsignage.hash]]"></script>

<!-- Clock Slide -->
<script type="text/javascript" src="/digitalsignage/assets/interface/javascript/slides/clock.slide.js?hash=[[!+digitalsignage.hash]]"></script>

<!-- Dumpert Slide -->
<script type="text/javascript" src="/digitalsignage/assets/interface/javascript/slides/dumpert.slide.js?hash=[[!+digitalsignage.hash]]"></script>

</body>
</html>