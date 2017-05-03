<!doctype html>
<html lang="{$_modx->config.cultureKey ?: 'nl'}" class="no-js">

    {include 'head'}
    
<body {$_modx->runSnippet('richSnippetWebPage')}>
    {include 'googleTagManager'}
    {include 'header'}

    <section id="content" itemprop="mainContentOfPage" itemscope="itemscope" itemtype="http://schema.org/WebPageElement">
        <div class="wrapper">
            <div class="row">
                <div class="block">
                    <div class="tinymce">
                        <h1 itemprop="headline">{$_modx->resource.longtitle ?: $_modx->resource.pagetitle}</h1>
                        <span itemprop="text">{$_modx->resource.content}</span>
                        
                        {$_modx->resource.contentExtender}
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    {include 'footer'}
    {include 'richSnippetSearchAction'}
    
</body>
</html>