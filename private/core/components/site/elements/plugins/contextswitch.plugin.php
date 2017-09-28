<?php
$pieces = explode('/', trim($_REQUEST[$modx->getOption('request_param_alias', null, 'q')], '/'), 2);
if (count($pieces) > 0) {
    $first = $pieces[0];
    unset($pieces[0]);
    $fullUrl = implode(',', $pieces);
    switch ($first) {
        case 'nieuwsbrieven':
            $_REQUEST[$modx->getOption('request_param_alias', null, 'q')] = (isset($pieces[1]) ? $fullUrl : '');
            $modx->switchContext('Nieuwsbrieven');
            break;
        default:
            break;
    }
}