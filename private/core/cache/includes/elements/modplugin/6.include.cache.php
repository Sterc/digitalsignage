<?php
/*
 * StatusOverrideIPs Plugin
 * Only runs when site is unavailable. Checks if user is allowed (by IP) and changes site_status to true when he is
 * */
if ($modx->event->name == 'OnHandleRequest' && isset($modx->config['site_status']) && $modx->config['site_status'] == false) {
    $modx->addPackage('statusoverrideips', $modx->getOption('core_path').'components/statusoverrideips/model/');
    if($modx->getCount('soIP', array('ip' => $_SERVER['REMOTE_ADDR']))) {
        $modx->config['site_status'] = true;
    }
}
return;
return;
