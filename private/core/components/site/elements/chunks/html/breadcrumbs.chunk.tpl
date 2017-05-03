<div class="breadcrumb">
    <div class="wrapper">
        <div class="row">
            <div class="breadcrumb--container">
                {$_modx->runSnippet('pdoCrumbs', [
                    'tplWrapper' => 'bcTplWrapper',
                    'tpl'        => 'bcTpl',
                    'tplCurrent' => 'bcTpl',
                    'showHome'   => 1
                ])}
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>