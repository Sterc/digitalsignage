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
                        {$_modx->resource.contentExtender}

                        <h2>Video</h2>
                        <div>
                            <h3>Thumbnail</h3>
                            <img src="{$_modx->runSnippet('oEmbed', [
                                'input'   => $_modx->resource.videoLink, 
                                'options' => 'thumbnail_url'
                            ])}" width="200" />
                            <h3>Embed</h3>
                            {$_modx->runSnippet('oEmbed', [
                                'input'   => $_modx->resource.videoLink, 
                                'options' => 'html'
                            ])}
                        </div>

                        <div class="map">
                            <div class="marker" data-lat="53.176194" data-lng="6.182732" data-icon="/assets/img/map/marker.png">
                                <p><strong>{$_modx->resource.pagetitle}</strong><br />
                                    Zoom 1<br />
                                    9231 DX Surhuisterveen</p>
                            </div>

                            <div class="marker" data-lat="53.186194" data-lng="6.142732">
                                <p><strong>{$_modx->resource.pagetitle}</strong><br />
                                    Zoom 1<br />
                                    9231 DX Surhuisterveen</p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
    
    {include 'footer'}

</body>
</html>