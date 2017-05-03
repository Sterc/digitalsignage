<div class="block block--fourth block--fourth__tablet">
    <div class="result--block">
        <a href="{$_modx->makeUrl($id)}" title="{$longtitle ?: $pagetitle}">
            <div class="result--item" style="
                {if !empty($_pls['tv.overviewImage']) }
                    {set $thumb = $_modx->runSnippet('phpthumbof', [
                        'input'   => $_pls['tv.overviewImage'],
                        'options' => 'w=400&h=400&zc=1'
                    ])}

                    background-image: url('{$thumb}');
                {/if}
            ">
                <span class="item--more"><i class="fa fa-chevron-up"></i></span>
                <div class="result--head">
                    {$longtitle ?: $pagetitle}
                </div>
                <div class="result--overlay">
                    {$introtext}
                </div>
            </div>
        </a>
    </div>
</div>
