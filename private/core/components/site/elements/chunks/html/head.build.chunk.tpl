<head>
    <!--[^qt^] s, [^q^] request(s), PHP: [^p^] s, total: [^t^] s, document retrieved from [^s^]-->
    {if $_modx->resource.id == $_modx->config.site_start}
        <title>{$_modx->resource.longtitle ?: $_modx->config.site_name}</title>
    {else}
        <title>{$_modx->resource.longtitle ?: $_modx->resource.pagetitle} | {$_modx->config.site_name}</title>
    {/if}
    <base href="{$_modx->config.site_url}">

    <meta charset="utf-8">
    {if !empty($_modx->resource.description)}
        <meta name="description" content="{$_modx->resource.description}">
    {/if}
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="robots" content="{$_pls['seoTab.robotsTag']}">

    <meta property="og:title" content="{$_modx->resource.longtitle ?: $_modx->resource.pagetitle} | {$_modx->config.site_name}">
    <meta property="og:description" content="{$_modx->resource.description ?: $_modx->resource.introtext}">
    <meta property="og:image" content="{$_modx->config.site_url} {$_modx->resource.mainImage}">
    <meta property="og:type" content="{$_modx->resource.id == $_modx->config.site_start ? 'website' : 'article'}">
    <meta property="og:site_name" content="{$_modx->config.site_name}">
    <meta property="og:url" content="{$_modx->makeUrl($_modx->resource.id, '', '', 'full')}">

    <!-- alleen wanneer de website is aangemeld bij Twitter:
    <meta name="twitter:card" content="summary"/>
    <meta name="twitter:url" content="{$_modx->makeUrl($_modx->resource.id, '', '', 'full')}"/>
    <meta name="twitter:title" content="{$_modx->resource.longtitle ?: $_modx->resource.pagetitle} | {$_modx->config.site_name}"/>
    <meta name="twitter:description" content="{$_modx->resource.description ?: $_modx->resource.introtext}"/>
    <meta name="twitter:image" content="{$_modx->config.site_url} {$_modx->resource.mainImage}"/>
    <meta name="twitter:creator:id" content="{$_modx->config.twitter_account}"/>
    -->

    <meta name="msapplication-TileImage" content="{$_modx->config.site_url}assets/img/metro-icon-win8.png">
    <meta name="msapplication-TileColor" content="#7a7a7a">

    <meta name="theme-color" content="#de007a">
    <meta name="msapplication-navbutton-color" content="#de007a">

    <link rel="canonical" href="{$_modx->makeUrl($_modx->resource.id, '', '', 'full')}">
    <link rel="shortcut icon" type="image/ico" href="assets/img/favicon.png">
    <!--[if lt IE 9]><link rel="shortcut icon" type="image/ico" href="{$_modx->makeUrl($_modx->resource.id)}assets/img/favicon.ico" /><![endif]-->
    <link rel="apple-touch-icon" sizes="144x144" href="assets/img/touch-icon.png">

    <link href="/above-the-fold.min.css?assets-inline" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{$_modx->runSnippet('!modxMinify', ['group' => 'css'])}">
</head>