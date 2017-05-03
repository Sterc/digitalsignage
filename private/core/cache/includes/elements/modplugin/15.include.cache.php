<?php
if($modx->resource->get('id') != $modx->config['site_start']) {
	$modx->resource->_output = str_replace('href="#','href="' .$modx->makeUrl($modx->resource->get('id')) .'#',$modx->resource->_output);
}
return;
