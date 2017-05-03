{if empty($link)}
    {var $linkUrl = $_modx->makeUrl($id)}
{else}
    {var $linkUrl = $link}
{/if}
<li>
    <h2>
        <a href="{$linkUrl}" title="{$longtitle}">{$pagetitle}</a>
    </h2>
    
    <div class="extract">
        <p>{$extract}</p>
    </div>
    
    <a href="{$linkUrl}" title="{$longtitle}">
        {$_modx->lexicon('site.read')}
    </a>
</li>