{$_modx->lexicon->load('site:default')} 
<a href="javascript:void(0)" class="btn-top" title=""><i class="fa fa-arrow-up"></i></a>

<div class="side-buttons">
    <a href="tel:00000000" class="phone" title="Bel ons"><i class="fa fa-phone"></i></a>
    <a href="mailto:info@sterc.nl" class="mail" title="Mail ons"><i class="fa fa-envelope-o"></i></a>
</div>
<div class="wrap">

    <!-- Start header -->
    <header class="header" itemscope itemtype="http://schema.org/WPHeader">
        <div class="wrapper wrapper--header">
            <div class="inner">
                <div class="block">
                    <div class="nav-toggle">
                        <div class="menu-toggle">
                            <div class="one"></div>
                            <div class="two"></div>
                            <div class="three"></div>
                        </div>
                        <div class="title-menu">
                            <span class="menu-title">Menu</span><span class="close-title">Sluit</span>
                        </div>
                    </div>
                    <a href="/" title="" class="logo">{$_modx->config.site_name}</a>
                    <nav class="main-navigation main-navigation__header" itemscope itemtype="http://schema.org/SiteNavigationElement">
                        {$_modx->runSnippet('pdoMenu', [
                            'parents'          => 0,
                            'outerClass'       => 'nav',
                            'innerClass'       => 'submenu',
                            'sortby'           => 'menuindex',
                            'sortdir'          => 'asc',
                            'limit'            => 0,
                            'level'            => 2,
                            'parentRowTpl'     => 'navParentRow',
                            'hideSubMenus'     => 0,
                            'checkPermissions' => 'load',
                            'fastMode'         => 1,
                            'tpl'              => '@INLINE <li{$_pls.classes}><a href="{$_pls.link}" {$_pls.attributes} itemprop="url"><span itemprop="name">{$_pls.menutitle}</span></a>{$_pls.wrapper}</li>'
                        ])}
                    </nav>
                </div>
                
                <div class="header--content">
                    <div class="search-container">
                        <form class="sisea-search-form" method="get" action="{$_modx->makeUrl($_modx->config.page_search)}"  data-alert="Vul een zoekopdracht in.">
                            <input type="text" class="search-form-input form-control" name="search" placeholder="{$_modx->lexicon('site.form.search.placeholder')}">
                            <input type="submit" class="search-form-button" value="{$_modx->lexicon('site.form.search.submit')}">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- End header -->
