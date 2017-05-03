</div>
<!-- End Wrapper -->

{include 'socialshare'}

<footer class="footer" itemscope itemtype="http://schema.org/WPFooter">
    <div class="footer-top">
        <div class="wrapper">
            <div class="inner">
                <div class="block block--third">
                    <div class="footer--box">
                        <h4>Heading</h4>
                        <ul class="list" itemscope itemtype="http://schema.org/SiteNavigationElement">
                            <li><a href="" title="" itemprop="url"><span itemprop="name">Item</span></a></li>
                            <li><a href="" title="" itemprop="url"><span itemprop="name">Item</span></a></li>
                            <li><a href="" title="" itemprop="url"><span itemprop="name">Item</span></a></li>
                        </ul>
                    </div>
                </div>
                <div class="block block--third">
                    <div class="footer--box">
                        <h4>Heading</h4>
                        <ul class="list" itemscope itemtype="http://schema.org/SiteNavigationElement">
                            <li><a href="" title="" itemprop="url"><span itemprop="name">Item</span></a></li>
                            <li><a href="" title="" itemprop="url"><span itemprop="name">Item</span></a></li>
                            <li><a href="" title="" itemprop="url"><span itemprop="name">Item</span></a></li>
                        </ul>
                    </div>
                </div>
                <div class="block block--third">
                    <div class="footer--box">
                        <h4>Heading</h4>
                        <ul class="list" itemscope itemtype="http://schema.org/SiteNavigationElement">
                            <li><a href="" title="" itemprop="url"><span itemprop="name">Item</span></a></li>
                            <li><a href="" title="" itemprop="url"><span itemprop="name">Item</span></a></li>
                            <li><a href="" title="" itemprop="url"><span itemprop="name">Item</span></a></li>
                        </ul>
                    </div>
                </div>
                <div class="block block--third">
                    <div class="footer--box">
                        <h4>Volg ons</h4>
                        <div class="follow">
                            <ul>
                                <li class="follow--item follow--item__facebook"><a href="#" target="_blank" title="" id="gtm-follow-facebook"><i class="fa fa-facebook"></i></a></li>
                                <li class="follow--item follow--item__twitter"><a href="#" target="_blank" title="" id="gtm-follow-twitter"><i class="fa fa-twitter"></i></a></li>
                                <li class="follow--item follow--item__twitter"><a href="#" target="_blank" title="" id="gtm-follow-twitter"><i class="fa fa-envelope"></i></a></li>
                                <li class="follow--item follow--item__youtube"><a href="#" target="_blank" title="" id="gtm-follow-youtube"><i class="fa fa-youtube"></i></a></li>
                                <li class="follow--item follow--item__linkedin"><a href="#" target="_blank" title="" id="gtm-follow-linkedin"><i class="fa fa-linkedin"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    <div class="wrapper">
        <div class="inner">
            <div class="block">
                <div class="main-navigation main-navigation__footer">
                    <div class="footer-copyright">Copyright &copy; <span itemprop="copyrightYear">2016</span> <span itemprop="copyrightHolder">{$_modx->config.site_name}</span></div>
                    
                    <ul itemscope itemtype="http://schema.org/SiteNavigationElement">
                        <li><a href="#" title="Privacy" itemprop="url"><span itemprop="name">Privacy &amp; Cookies</span></a></li>
                        <li><a href="#" title="Disclaimer" itemprop="url"><span itemprop="name">Disclaimer</span></a></li>
                        <li><a href="#" title="Sitemap" itemprop="url"><span itemprop="name">Sitemap</span></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>

{if !empty($_modx->config.google_maps_api_key)}
    <script>var GoogleMapsApiKey = "{$_modx->config.google_maps_api_key}";</script>
{/if}

<script src="{$_modx->config.server_protocol}://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="{$_modx->runSnippet('!modxMinify', ['group' => 'js'])}"></script>