<!doctype html>
<html lang="{$_modx->config.cultureKey ?: 'nl'}" class="no-js">
    {include 'head'}
    
<body {$_modx->runSnippet('richSnippetWebPage')}>
    {include 'googleTagManager'}
    {include 'header'}
    {include 'breadcrumbs'}

    <section itemscope itemtype="http://schema.org/NewsArticle">
        <div class="wrapper">
            <div class="inner">

                <div class="block block--six block--six__center">
                    <div class="tinymce">
                        <h1 itemprop="headline">{$_modx->resource.longtitle ?: $_modx->resource.pagetitle}</h1>
                        <p class="date">
                            {set $datePublished = $_modx->runSnippet('getSnippet', [
                                'formatDate',
                                [
                                    'input'   => $_modx->resource.publishedon,
                                    'options' => '%Y-%m-%d'
                                ]
                            ])}
                            
                            <meta itemprop="datePublished" content="{$datePublished}">
                            {$_modx->runSnippet('getSnippet', [
                                'formatDate',
                                [
                                    'input' => $_modx->resource.publishedon
                                ]
                            ])}
                        </p>
                        
                        <span itemprop="articleSection" content="News"></span>
                        <span itemprop="author" content="MODX User"></span>
                        <span itemprop="publisher" content="{$_modx->config.site_name}"></span>
                        {if !empty($_modx->resource.mainImage)}
                            <img itemprop="image" src="{$_modx->runSnippet('pthumb', ['input' => $_modx->resource.mainImage, 'options' => 'w=600&h=300&zc=1'])}" alt="{$_modx->resource.mainImageAlt}" title="{$_modx->resource.mainImageAlt}" width="600" height="300" />
                        {/if}
                        
                        <span itemprop="articleBody">{$_modx->resource.content}</span>
                        {$_modx->resource.contentExtender}
                    </div>
                </div>
            </div>
        </div>
    </section>

    {include 'footer'}

</body>
</html>