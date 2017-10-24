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
                <img src="/digitalsignage/assets/interface/images/logo-text.svg" />
            </div>

            <!-- Begin Clock Plugin -->
            <div class="clock" data-plugin="ClockPlugin">
                <div class="date" data-placeholder="date" data-placeholder-renders="date:%d %F %Y"></div>
                <div class="time" data-placeholder="time" data-placeholder-renders="date:%H:%I uur"></div>
            </div>
            <!-- End Clock Plugin -->

            <!-- Begin Social Media Plugin -->
            <div class="social-media" data-plugin="SocialMediaPlugin" data-plugin-settings="{'feed': '/ds/facebook-export.json', 'feedType': 'JSON'}">
                <div class="social-media-inner" data-placeholder="social-media">
                    <div class="social-media-item" data-template="item">
                        <div class="image">
                            <img src="" data-placeholder="image" data-placeholder-wrapper="image" />
                        </div>
                        <div class="content">
                            <p data-placeholder="content" data-placeholder-renders="striptags,ellipsis:100"></p>
                            <p class="date" data-placeholder="added" data-placeholder-renders="date:%d %F %Y"></p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Social Media Plugin -->
        </div>

        <!-- Begin Newsticker Plugin -->
        <div class="ticker" data-plugin="NewstickerPlugin" data-plugin-settings="{'feed': '[[!+digitalsignage.broadcast.feed]]', 'feedType': 'JSON'}">
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
            <!-- Begin Default Slide -->
            <div data-template="default">
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
            </div>
            <!-- End Default Slide -->
            <!-- Begin Media Slide -->
            <div data-template="media">
                <div class="slide slide-media">
                    <div class="slide-inner">
                        <div class="content">
                            <iframe src="" data-placeholder="youtube" data-placeholder-renders="youtube" class="video" frameborder="0" allowfullscreen></iframe>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Media Slide -->
            <!-- Begin Facebook Slide -->
            <div data-template="facebook">
                <div class="slide slide-facebook">
                    <div class="slide-inner">
                        <div class="image">
                            <img src="" data-placeholder="image" data-placeholder-wrapper="image" />
                        </div>
                        <div class="content">
                            <div class="content-mask">
                                <h1 data-placeholder="title" data-placeholder-renders="striptags,ellipsis:150"></h1>
                                <div data-placeholder="content" data-placeholder-renders="striptags:p|h2|h3|h4|strong|em|span|br|ul|ol|li"></div>
                                <p class="author" data-placeholder="author"></p>
                                <p class="date" data-placeholder="pubDate" data-placeholder-renders="date:%d %F %Y"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Facebook Slide -->
            <!-- Begin Buienradar Slide -->
            <div data-template="buienradar">
                <div class="slide slide-buienradar">
                    <video class="background-video" autoplay>
                        <source src="/digitalsignage/assets/interface/images/buienradar/buienradar.mp4" type="video/mp4">
                    </video>
                    <div class="slide-inner">
                        <div class="image">
                            <img src="" data-placeholder="radar" data-placeholder-wrapper="image" />
                        </div>
                        <div class="content">
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
            </div>
            <!-- End Buienradar Slide -->
            <!-- Begin Success Rate Slide -->
            <div data-template="success-rate">
                <div class="slide slide-success-rate">
                    <div class="slide-inner">
                        <div class="content">
                            <h1 data-placeholder="title" data-placeholder-renders="striptags,ellipsis:150"></h1>
                            <div class="table">
                                <div class="table-head">
                                    <span class="table-column table-column-name">Naam</span>
                                    <span class="table-column table-column-commits">Commits</span>
                                    <span class="table-column table-column-rate">Punten</span>
                                </div>
                                <div class="table-body" data-placeholder="rates">
                                    <div class="table-row" data-template="rate">
                                            <span class="table-column table-column-name">
                                                <span data-placeholder="idx"></span>. <span data-placeholder="name"></span>
                                            </span>
                                        <span class="table-column table-column-commits">
                                                <span class="rate-success">
                                                    <span class="icon"></span>
                                                    <span data-placeholder="success"></span>
                                                </span>
                                                 /
                                                <span class="rate-failed">
                                                    <span data-placeholder="failed"></span>
                                                    <span class="icon"></span>
                                                </span>
                                            </span>
                                        <span class="table-column table-column-score">
                                                <span data-placeholder="score"></span>
                                            </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Success Rate Slide -->
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

    <script type="text/javascript" src="/digitalsignage/assets/interface/javascript/socialmedia.plugin.js?hash=[[!+digitalsignage.hash]]"></script>
    <script type="text/javascript" src="/digitalsignage/assets/interface/javascript/successrate.slide.js?hash=[[!+digitalsignage.hash]]"></script>
    <script type="text/javascript" src="/digitalsignage/assets/interface/javascript/buienradar.slide.js?hash=[[!+digitalsignage.hash]]"></script>
    <script type="text/javascript" src="/digitalsignage/assets/interface/javascript/digitalsignage.js?hash=[[!+digitalsignage.hash]]"></script>

</body>
</html>
