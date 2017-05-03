{if $_modx->resource.richSnippetSearchAction == 1}
    <script type="application/ld+json">
    {ignore}
    {
        "@context": "http://schema.org",
        "@type": "WebSite",
        "url": "{$_modx->makeUrl($_modx->config.site_start, '', '', 'full')}",
        "potentialAction": {
            "@type": "SearchAction",
            "target": "{$_modx->makeUrl($_modx->config.page_search, '', '', 'full')}?search={'{search_term_string}'}",
            "query-input": "required name=search_term_string"
        }
    }
    {/ignore}
    </script>
{/if}