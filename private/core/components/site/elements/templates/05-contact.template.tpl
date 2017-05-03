<!doctype html>
<html lang="{$_modx->config.cultureKey ?: 'nl'}" class="no-js">
    {include 'head'}

<body {$_modx->runSnippet('richSnippetWebPage')}>
    {include 'googleTagManager'}
    {include 'header'}
    {include 'breadcrumbs'}

    <section itemprop="mainContentOfPage" itemscope="itemscope" itemtype="http://schema.org/WebPageElement">
        <div class="wrapper">
            <div class="inner">

                <div class="block block--six block--six__center">
                    <div class="tinymce">
                        <h1 itemprop="headline">{$_modx->resource.longtitle ?: $_modx->resource.pagetitle}</h1>

                        {if !empty($_modx->resource.mainImage)}
                            <img itemprop="image" src="{$_modx->runSnippet('pthumb', ['input' => $_modx->resource.mainImage, 'options' => 'w=600&h=300&zc=1'])}" alt="{$_modx->resource.mainImageAlt}" title="{$_modx->resource.mainImageAlt}" width="600" height="300" />
                        {/if}

                        <span itemprop="text">{$_modx->resource.content}</span>

                        {$_modx->getChunk('contactForm')}

                        <div class="maps-container">
                            <div id="gmapOverlay" style="display:none;"></div>
                            <div class="map">
                                <span data-api-key="{$_modx->config.site.googlemaps.api_key}"></span>
                                <div class="marker" data-lat="53.176194" data-lng="6.182732" data-icon="/assets/img/map/marker.png">
                                    <p><strong>{$_modx->resource.pagetitle}</strong><br />
                                        Zoom 1<br />
                                        9231 DX Surhuisterveen
                                    </p>
                                </div>
                            </div>
                        </div>

                        <a href="https://www.google.com/maps/dir/Current+Location/Zoom+1+Surhuisterveen" target="_blank" title="{$_modx->lexicon('site.plan_route')}" class="btn btn-primary btn-block">{$_modx->lexicon('site.plan_route')}</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {include 'footer'}

</body>
</html>
