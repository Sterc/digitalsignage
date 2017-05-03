<?php
/**
 * Retrieve snippet from cache or return uncached snippet output.
 *
 * Examples:
 *
 * Return cached:
 * $modx->runSnippet('getSnippet', ['getResources', ['limit' => 1], 3600, 'customCacheKey']);
 *
 * Return uncached, prepend with "!"
 * $modx->runSnippet('getSnippet', ['!getResources', ['limit' => 1], 3600, 'customCacheKey']);
 */
$snippet      = $scriptProperties[0];
$options      = (is_array($scriptProperties[1])) ? $scriptProperties[1] : [];
$cacheTime    = (isset($scriptProperties[3])) ? $scriptProperties[3] : 7200;
$cacheKey     = (isset($scriptProperties[4])) ? $scriptProperties[4] : '';
$cacheOptions = [xPDO::OPT_CACHE_KEY => 'getsnippets'];
$fromCache    = true;

if (!is_string($snippet) || empty($snippet)) {
    return '';
}

if (substr($snippet, 0, 1) === '!') {
    $fromCache = false;
}
$snippet = ltrim($snippet, '!');

if ($fromCache) {
    if (empty($cacheKey)) {
        $cacheKey = strtolower($snippet) . '-' . md5(implode(',', $options));
    }
    
    $cache = $modx->cacheManager->get($cacheKey, $cacheOptions);
    if ($cache) {
        $result = $cache;
    } else {
        $result = $modx->runSnippet($snippet, $options);
        $modx->cacheManager->set($cacheKey, $result, $cacheTime, $cacheOptions);
    }
} else {
    $result = $modx->runSnippet($snippet, $options);
}

return $result;
