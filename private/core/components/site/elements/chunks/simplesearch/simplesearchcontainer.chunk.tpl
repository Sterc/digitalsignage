<p class="sisea-results">
    {$resultInfo}
</p>

<div class="sisea-results-list">
    <ul class="search-results">
        {$results}
    </ul>
</div>
{if $total > 10}
    <div class="sisea-paging">
        <span class="sisea-result-pages">
            <p>{$_modx->lexicon('sisea.result_pages')}</p>
        </span>
        {$paging}
    </div>
{/if}