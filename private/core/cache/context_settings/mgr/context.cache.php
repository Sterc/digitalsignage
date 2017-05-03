<?php  return array (
  'config' => 
  array (
    'allow_tags_in_post' => '1',
    'modRequest.class' => 'modManagerRequest',
  ),
  'aliasMap' => 
  array (
  ),
  'webLinkMap' => 
  array (
  ),
  'eventMap' => 
  array (
    'OnBeforeDocFormSave' => 
    array (
      25 => '25',
      14 => '14',
    ),
    'OnBeforeEmptyTrash' => 
    array (
      25 => '25',
    ),
    'OnBeforeSaveWebPageCache' => 
    array (
      31 => '31',
    ),
    'OnChunkFormPrerender' => 
    array (
      18 => '18',
      12 => '12',
      23 => '23',
    ),
    'OnChunkFormSave' => 
    array (
      23 => '23',
      12 => '12',
    ),
    'OnDocFormPrerender' => 
    array (
      14 => '14',
      25 => '25',
      12 => '12',
      23 => '23',
      18 => '18',
      2 => '2',
    ),
    'OnDocFormRender' => 
    array (
      25 => '25',
      19 => '19',
    ),
    'OnDocFormSave' => 
    array (
      19 => '19',
      14 => '14',
      12 => '12',
      23 => '23',
      5 => '5',
    ),
    'OnDocPublished' => 
    array (
      5 => '5',
    ),
    'OnDocUnPublished' => 
    array (
      5 => '5',
    ),
    'OnEmptyTrash' => 
    array (
      14 => '14',
    ),
    'OnFileCreateFormPrerender' => 
    array (
      18 => '18',
    ),
    'OnFileEditFormPrerender' => 
    array (
      18 => '18',
    ),
    'OnFileManagerDirCreate' => 
    array (
      17 => '17',
    ),
    'OnFileManagerDirRename' => 
    array (
      17 => '17',
    ),
    'OnFileManagerUpload' => 
    array (
      17 => '17',
    ),
    'OnHandleRequest' => 
    array (
      32 => '32',
      6 => '6',
      1 => '1',
      10 => '10',
      13 => '13',
      14 => '14',
    ),
    'OnLoadWebDocument' => 
    array (
      14 => '14',
      33 => '33',
      19 => '19',
    ),
    'OnLoadWebPageCache' => 
    array (
      32 => '32',
    ),
    'OnManagerPageBeforeRender' => 
    array (
      14 => '14',
      18 => '18',
      25 => '25',
    ),
    'OnManagerPageInit' => 
    array (
      25 => '25',
    ),
    'OnMODXInit' => 
    array (
      31 => '31',
      1 => '1',
    ),
    'OnPageNotFound' => 
    array (
      14 => '14',
    ),
    'OnPluginFormPrerender' => 
    array (
      12 => '12',
      18 => '18',
    ),
    'OnPluginFormSave' => 
    array (
      12 => '12',
    ),
    'OnResourceBeforeSort' => 
    array (
      25 => '25',
      14 => '14',
    ),
    'OnResourceDelete' => 
    array (
      5 => '5',
    ),
    'OnResourceDuplicate' => 
    array (
      14 => '14',
      19 => '19',
      5 => '5',
    ),
    'OnResourceUndelete' => 
    array (
      5 => '5',
    ),
    'OnRichTextBrowserInit' => 
    array (
      29 => '29',
    ),
    'OnRichTextEditorInit' => 
    array (
      29 => '29',
    ),
    'OnRichTextEditorRegister' => 
    array (
      18 => '18',
      29 => '29',
    ),
    'OnSiteRefresh' => 
    array (
      30 => '30',
      16 => '16',
      31 => '31',
    ),
    'OnSnipFormPrerender' => 
    array (
      12 => '12',
      18 => '18',
      23 => '23',
    ),
    'OnSnipFormSave' => 
    array (
      12 => '12',
    ),
    'OnTempFormPrerender' => 
    array (
      23 => '23',
      12 => '12',
      18 => '18',
    ),
    'OnTempFormSave' => 
    array (
      23 => '23',
      12 => '12',
    ),
    'OnTVFormPrerender' => 
    array (
      12 => '12',
    ),
    'OnTVFormSave' => 
    array (
      12 => '12',
    ),
    'OnTVInputPropertiesList' => 
    array (
      2 => '2',
    ),
    'OnTVInputRenderList' => 
    array (
      2 => '2',
    ),
    'OnWebPageInit' => 
    array (
      32 => '32',
    ),
    'OnWebPagePrerender' => 
    array (
      15 => '15',
      32 => '32',
    ),
  ),
  'pluginCache' => 
  array (
    1 => 
    array (
      'id' => '1',
      'source' => '0',
      'property_preprocess' => '0',
      'name' => 'ClientConfig',
      'description' => 'Sets system settings from the Client Config CMP.',
      'editor_type' => '0',
      'category' => '0',
      'cache_type' => '0',
      'plugincode' => '/**
 * ClientConfig
 *
 * Copyright 2011-2014 by Mark Hamstra <hello@markhamstra.com>
 *
 * ClientConfig is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * ClientConfig is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * ClientConfig; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package clientconfig
 *
 * @var modX $modx
 * @var int $id
 * @var string $mode
 * @var modResource $resource
 * @var modTemplate $template
 * @var modTemplateVar $tv
 * @var modChunk $chunk
 * @var modSnippet $snippet
 * @var modPlugin $plugin
*/

$eventName = $modx->event->name;

switch($eventName) {
    case \'OnMODXInit\':
        /* Grab the class */
        $path = $modx->getOption(\'clientconfig.core_path\', null, $modx->getOption(\'core_path\') . \'components/clientconfig/\');
        $path .= \'model/clientconfig/\';
        $clientConfig = $modx->getService(\'clientconfig\',\'ClientConfig\', $path);

        /* If we got the class (gotta be careful of failed migrations), grab settings and go! */
        if ($clientConfig instanceof ClientConfig) {
            $settings = $clientConfig->getSettings();

            /* Make settings available as [[++tags]] */
            $modx->setPlaceholders($settings, \'+\');

            /* Make settings available for $modx->getOption() */
            foreach ($settings as $key => $value) {
                $modx->setOption($key, $value);
            }
        }
        break;
}

return;',
      'locked' => '0',
      'properties' => NULL,
      'disabled' => '0',
      'moduleguid' => '',
      'static' => '0',
      'static_file' => '',
    ),
    2 => 
    array (
      'id' => '2',
      'source' => '0',
      'property_preprocess' => '0',
      'name' => 'MIGX',
      'description' => '',
      'editor_type' => '0',
      'category' => '8',
      'cache_type' => '0',
      'plugincode' => '$corePath = $modx->getOption(\'migx.core_path\',null,$modx->getOption(\'core_path\').\'components/migx/\');
$assetsUrl = $modx->getOption(\'migx.assets_url\', null, $modx->getOption(\'assets_url\') . \'components/migx/\');
switch ($modx->event->name) {
    case \'OnTVInputRenderList\':
        $modx->event->output($corePath.\'elements/tv/input/\');
        break;
    case \'OnTVInputPropertiesList\':
        $modx->event->output($corePath.\'elements/tv/inputoptions/\');
        break;

        case \'OnDocFormPrerender\':
        $modx->controller->addCss($assetsUrl.\'css/mgr.css\');
        break; 
 
    /*          
    case \'OnTVOutputRenderList\':
        $modx->event->output($corePath.\'elements/tv/output/\');
        break;
    case \'OnTVOutputRenderPropertiesList\':
        $modx->event->output($corePath.\'elements/tv/properties/\');
        break;
    
    case \'OnDocFormPrerender\':
        $assetsUrl = $modx->getOption(\'colorpicker.assets_url\',null,$modx->getOption(\'assets_url\').\'components/colorpicker/\'); 
        $modx->regClientStartupHTMLBlock(\'<script type="text/javascript">
        Ext.onReady(function() {
            
        });
        </script>\');
        $modx->regClientStartupScript($assetsUrl.\'sources/ColorPicker.js\');
        $modx->regClientStartupScript($assetsUrl.\'sources/ColorMenu.js\');
        $modx->regClientStartupScript($assetsUrl.\'sources/ColorPickerField.js\');		
        $modx->regClientCSS($assetsUrl.\'resources/css/colorpicker.css\');
        break;
     */
}
return;',
      'locked' => '0',
      'properties' => 'a:0:{}',
      'disabled' => '0',
      'moduleguid' => '',
      'static' => '0',
      'static_file' => '',
    ),
    5 => 
    array (
      'id' => '5',
      'source' => '0',
      'property_preprocess' => '0',
      'name' => 'SimpleSearchIndexer',
      'description' => 'Automatically indexes Resources into Solr.',
      'editor_type' => '0',
      'category' => '0',
      'cache_type' => '0',
      'plugincode' => '/**
 * SimpleSearch
 *
 * Copyright 2010-11 by Shaun McCormick <shaun+sisea@modx.com>
 *
 * This file is part of SimpleSearch, a simple search component for MODx
 * Revolution. It is loosely based off of AjaxSearch for MODx Evolution by
 * coroico/kylej, minus the ajax.
 *
 * SimpleSearch is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * SimpleSearch is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
 * details.
 *
 * You should have received a copy of the GNU General Public License along with
 * SimpleSearch; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
 * Suite 330, Boston, MA 02111-1307 USA
 *
 * @package simplesearch
 */
/**
 * Plugin to index Resources whenever they are changed, published, unpublished,
 * deleted, or undeleted.
 *
 * @var modX $modx
 * @var SimpleSearch $search
 *
 * @package simplesearch
 */

require_once $modx->getOption(\'sisea.core_path\',null,$modx->getOption(\'core_path\').\'components/simplesearch/\').\'model/simplesearch/simplesearch.class.php\';
$search = new SimpleSearch($modx,$scriptProperties);

$search->loadDriver($scriptProperties);
if (!$search->driver || (!($search->driver instanceof SimpleSearchDriverSolr) && !($search->driver instanceof SimpleSearchDriverElastic))) return;

/**
 * helper method for missing params in events
 * @param modX $modx
 * @param array $children
 * @param int $parent
 * @return boolean
 */
if (!function_exists(\'SimpleSearchGetChildren\')) {
    function SimpleSearchGetChildren(&$modx,&$children,$parent) {
        $success = false;
        $kids = $modx->getCollection(\'modResource\',array(
            \'parent\' => $parent,
        ));
        if (!empty($kids)) {
            /** @var modResource $kid */
            foreach ($kids as $kid) {
                $children[] = $kid->toArray();
                SimpleSearchGetChildren($modx,$children,$kid->get(\'id\'));
            }
        }
        return $success;
    }
}

$action = \'index\';
$resourcesToIndex = array();
switch ($modx->event->name) {
    case \'OnDocFormSave\':
        $action = \'index\';
        $resourceArray = $scriptProperties[\'resource\']->toArray();

        if ($resourceArray[\'published\'] == 1 && $resourceArray[\'deleted\'] == 0) {
            $action = \'index\';
            foreach ($_POST as $k => $v) {
                if (substr($k,0,2) == \'tv\') {
                    $id = str_replace(\'tv\',\'\',$k);
                    /** @var modTemplateVar $tv */
                    $tv = $modx->getObject(\'modTemplateVar\',$id);
                    if ($tv) {
                        $resourceArray[$tv->get(\'name\')] = $tv->renderOutput($resource->get(\'id\'));
                        $modx->log(modX::LOG_LEVEL_DEBUG,\'Indexing \'.$tv->get(\'name\').\': \'.$resourceArray[$tv->get(\'name\')]);
                    }
                    unset($resourceArray[$k]);
                }
            }
        } else {
            $action = \'removeIndex\';
        }

        unset($resourceArray[\'ta\'],$resourceArray[\'action\'],$resourceArray[\'tiny_toggle\'],$resourceArray[\'HTTP_MODAUTH\'],$resourceArray[\'modx-ab-stay\'],$resourceArray[\'resource_groups\']);
        $resourcesToIndex[] = $resourceArray;
        break;
    case \'OnDocPublished\':
        $action = \'index\';
        $resourceArray = $scriptProperties[\'resource\']->toArray();
        unset($resourceArray[\'ta\'],$resourceArray[\'action\'],$resourceArray[\'tiny_toggle\'],$resourceArray[\'HTTP_MODAUTH\'],$resourceArray[\'modx-ab-stay\'],$resourceArray[\'resource_groups\']);
        $resourcesToIndex[] = $resourceArray;
        break;
    case \'OnDocUnpublished\':
    case \'OnDocUnPublished\':
        $action = \'removeIndex\';
        $resourceArray = $scriptProperties[\'resource\']->toArray();
        unset($resourceArray[\'ta\'],$resourceArray[\'action\'],$resourceArray[\'tiny_toggle\'],$resourceArray[\'HTTP_MODAUTH\'],$resourceArray[\'modx-ab-stay\'],$resourceArray[\'resource_groups\']);
        $resourcesToIndex[] = $resourceArray;
        break;
    case \'OnResourceDuplicate\':
        $action = \'index\';
        /** @var modResource $newResource */
        $resourcesToIndex[] = $newResource->toArray();
        $children = array();
        SimpleSearchGetChildren($modx,$children,$newResource->get(\'id\'));
        foreach ($children as $child) {
            $resourcesToIndex[] = $child;
        }
        break;
    case \'OnResourceDelete\':
        $action = \'removeIndex\';
        $resourcesToIndex[] = $resource->toArray();
        $children = array();
        SimpleSearchGetChildren($modx,$children,$resource->get(\'id\'));
        foreach ($children as $child) {
            $resourcesToIndex[] = $child;
        }
        break;
    case \'OnResourceUndelete\':
        $action = \'index\';
        $resourcesToIndex[] = $resource->toArray();
        $children = array();
        SimpleSearchGetChildren($modx,$children,$resource->get(\'id\'));
        foreach ($children as $child) {
            $resourcesToIndex[] = $child;
        }
        break;
}

foreach ($resourcesToIndex as $resourceArray) {
    if (!empty($resourceArray[\'id\'])) {
        if ($action == \'index\') {
            $search->driver->index($resourceArray);
        } else if ($action == \'removeIndex\') {
            $search->driver->removeIndex($resourceArray[\'id\']);
        }
    }
}
return;',
      'locked' => '0',
      'properties' => NULL,
      'disabled' => '0',
      'moduleguid' => '',
      'static' => '0',
      'static_file' => '',
    ),
    6 => 
    array (
      'id' => '6',
      'source' => '0',
      'property_preprocess' => '0',
      'name' => 'StatusOverrideIPs',
      'description' => 'Only allow some IPs to access front end when site is unavailable',
      'editor_type' => '0',
      'category' => '11',
      'cache_type' => '0',
      'plugincode' => '/*
 * StatusOverrideIPs Plugin
 * Only runs when site is unavailable. Checks if user is allowed (by IP) and changes site_status to true when he is
 * */
if ($modx->event->name == \'OnHandleRequest\' && isset($modx->config[\'site_status\']) && $modx->config[\'site_status\'] == false) {
    $modx->addPackage(\'statusoverrideips\', $modx->getOption(\'core_path\').\'components/statusoverrideips/model/\');
    if($modx->getCount(\'soIP\', array(\'ip\' => $_SERVER[\'REMOTE_ADDR\']))) {
        $modx->config[\'site_status\'] = true;
    }
}
return;',
      'locked' => '0',
      'properties' => NULL,
      'disabled' => '0',
      'moduleguid' => '',
      'static' => '0',
      'static_file' => '',
    ),
    10 => 
    array (
      'id' => '10',
      'source' => '4',
      'property_preprocess' => '0',
      'name' => 'contextSwitch',
      'description' => 'Switches to the correct context, based on the first part after the first slash ( / ).',
      'editor_type' => '0',
      'category' => '0',
      'cache_type' => '0',
      'plugincode' => '$pieces = explode(\'/\', trim($_REQUEST[$modx->getOption(\'request_param_alias\', null, \'q\')], \'/\'), 2);
if (count($pieces) > 0) {
    $first = $pieces[0];
    unset($pieces[0]);
    $fullUrl = implode(\',\', $pieces);
    switch ($first) {
        case \'nieuwsbrieven\':
            $_REQUEST[$modx->getOption(\'request_param_alias\', null, \'q\')] = (isset($pieces[1]) ? $fullUrl : \'\');
            $modx->switchContext(\'Nieuwsbrieven\');
            break;
        case \'nc\':
            $_REQUEST[$modx->getOption(\'request_param_alias\', null, \'q\')] = (isset($pieces[1]) ? $fullUrl : \'\');
            $modx->switchContext(\'nc\');
            break;
        default:
            break;
    }
}',
      'locked' => '0',
      'properties' => 'a:0:{}',
      'disabled' => '0',
      'moduleguid' => '',
      'static' => '1',
      'static_file' => 'components/site/elements/plugins/contextswitch.plugin.php',
    ),
    12 => 
    array (
      'id' => '12',
      'source' => '0',
      'property_preprocess' => '0',
      'name' => 'VersionX',
      'description' => 'The plugin that enables VersionX of tracking your content.',
      'editor_type' => '0',
      'category' => '0',
      'cache_type' => '0',
      'plugincode' => '$corePath = $modx->getOption(\'versionx.core_path\',null,$modx->getOption(\'core_path\').\'components/versionx/\');
require_once $corePath.\'model/versionx.class.php\';
$modx->versionx = new VersionX($modx);

include $corePath . \'elements/plugins/versionx.plugin.php\';
return;',
      'locked' => '0',
      'properties' => NULL,
      'disabled' => '0',
      'moduleguid' => '',
      'static' => '0',
      'static_file' => '',
    ),
    13 => 
    array (
      'id' => '13',
      'source' => '4',
      'property_preprocess' => '0',
      'name' => 'childTemplate',
      'description' => '',
      'editor_type' => '0',
      'category' => '0',
      'cache_type' => '0',
      'plugincode' => 'if (isset($_GET[\'a\'])) {
    $action = $_GET[\'a\'];
    if (!empty($action) && $action == \'resource/create\') {
        $parentID = isset($_REQUEST[\'parent\']) ? (int) $_REQUEST[\'parent\'] : 0;
        if ($parent = $modx->getObject(\'modResource\', $parentID)) {
            $parentTpl = $parent->get(\'template\');
            if ($parentTplObj = $modx->getObject(\'modTemplate\', $parentTpl)) {
                if ($props = $parentTplObj->getProperties()) {
                    $tpl = ($ff = $modx->fromJSON($props[\'childTemplate\'])) ? $ff : null;
                    if ($tpl) {
                        $_GET[\'template\'] = $tpl;
                    }
                }
            }
        }
    }
}',
      'locked' => '0',
      'properties' => 'a:0:{}',
      'disabled' => '0',
      'moduleguid' => '',
      'static' => '1',
      'static_file' => 'components/site/elements/plugins/childtemplate.plugin.php',
    ),
    14 => 
    array (
      'id' => '14',
      'source' => '0',
      'property_preprocess' => '0',
      'name' => 'StercSEO',
      'description' => 'Plugin to render SEO Tab and save all the data.',
      'editor_type' => '0',
      'category' => '23',
      'cache_type' => '0',
      'plugincode' => '/**
 * SEO Tab
 *
 * Copyright 2013 by Sterc internet & marketing <modx@sterc.nl>
 *
 * This file is part of SEO Tab.
 *
 * SEO Tab is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * SEO Tab is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * SEO Tab; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
 * Suite 330, Boston, MA 02111-1307 USA
 *
 * @package stercseo
 */
/**
 * SEO Tab Plugin
 *
 *
 * Events:
 * OnDocFormPrerender,OnDocFormSave,OnHandleRequest,OnPageNotFound, OnResourceDuplicate, OnEmptyThrash, OnResourceBeforeSort
 *
 * @author Sterc internet & marketing <modx@sterc.nl>
 *
 * @package stercseo
 *
 */
$stercseo = $modx->getService(\'stercseo\', \'StercSEO\', $modx->getOption(\'stercseo.core_path\', null, $modx->getOption(\'core_path\').\'components/stercseo/\').\'model/stercseo/\', array());

if (!($stercseo instanceof StercSEO)) {
    return;
}

switch ($modx->event->name) {
    case \'OnDocFormPrerender\':
        if (!$stercseo->checkUserAccess()) {
            return;
        }

        $resource =& $modx->event->params[\'resource\'];
        if ($resource) {
            //First check if SEO Tab is allowed in this context
            if (!$stercseo->isAllowed($resource->get(\'context_key\'))) {
                return;
            }
            $properties = $resource->getProperties(\'stercseo\');
            $urls = $modx->getCollection(\'seoUrl\', array(\'resource\' => $resource->get(\'id\')));
        }

        if (empty($properties)) {
            $properties = array(
                \'index\' => $modx->getOption(\'stercseo.index\', null, \'1\'),
                \'follow\' => $modx->getOption(\'stercseo.follow\', null, \'1\'),
                \'sitemap\' => $modx->getOption(\'stercseo.sitemap\', null, \'1\'),
                \'priority\' => $modx->getOption(\'stercseo.priority\', null, \'0.5\'),
                \'changefreq\' => $modx->getOption(\'stercseo.changefreq\', null, \'weekly\')
            );
        }
        $properties[\'urls\'] = \'\';
        // Fetch urls from seoUrl collection
        if ($urls && is_object($urls)) {
            foreach ($urls as $url) {
                $properties[\'urls\'][][\'url\'] = urldecode($url->get(\'url\'));
            }
        }

        $modx->regClientStartupHTMLBlock(\'<script type="text/javascript">
        Ext.onReady(function() {
            StercSEO.config = \'.$modx->toJSON($stercseo->config).\';
            StercSEO.config.connector_url = "\'.$stercseo->config[\'connectorUrl\'].\'";
            StercSEO.record = \'.$modx->toJSON($properties).\';
        });
        </script>\');
        $version = $modx->getVersionData();

        /* include CSS and JS*/
        if ($version[\'version\'] == 2 && $version[\'major_version\'] == 2) {
            $modx->regClientCSS($stercseo->config[\'cssUrl\'].\'stercseo.css\');
        }
        $modx->regClientStartupScript($stercseo->config[\'jsUrl\'].\'mgr/stercseo.js\');
        $modx->regClientStartupScript($stercseo->config[\'jsUrl\'].\'mgr/sections/resource.js\');
        $modx->regClientStartupScript($stercseo->config[\'jsUrl\'].\'mgr/widgets/resource.grid.js\');
        $modx->regClientStartupScript($stercseo->config[\'jsUrl\'].\'mgr/widgets/resource.vtabs.js\');

        //add lexicon
        $modx->controller->addLexiconTopic(\'stercseo:default\');

        break;

    case \'OnBeforeDocFormSave\':
        $oldResource = ($mode == \'upd\') ? $modx->getObject(\'modResource\', $resource->get(\'id\')) : $resource;
        if (!$stercseo->isAllowed($oldResource->get(\'context_key\'))) {
            return;
        }
        $properties = $oldResource->getProperties(\'stercseo\');

        if (isset($_POST[\'urls\'])) {
            $urls = $modx->fromJSON($_POST[\'urls\']);
            foreach ($urls as $url) {
                $check = $modx->getObject(\'seoUrl\', array( \'url\' => urlencode($url[\'url\']), \'resource\' => $oldResource->get(\'id\'), \'context_key\' => $oldResource->get(\'context_key\')));
                if (!$check) {
                    $redirect = $modx->newObject(\'seoUrl\');
                    $data = array(
                        \'url\' => urlencode($url[\'url\']),
                        \'resource\' => $oldResource->get(\'id\'),
                        \'context_key\' => $oldResource->get(\'context_key\'),
                    );
                    $redirect->fromArray($data);
                    $redirect->save();
                }
            }
        }

        if ($mode == \'upd\') {
            $newProperties = array(
                \'index\' => (isset($_POST[\'index\']) ? $_POST[\'index\'] : $properties[\'index\']),
                \'follow\' => (isset($_POST[\'follow\']) ? $_POST[\'follow\'] : $properties[\'follow\']),
                \'sitemap\' => (isset($_POST[\'sitemap\']) ? $_POST[\'sitemap\'] : $properties[\'sitemap\']),
                \'priority\' => (isset($_POST[\'priority\']) ? $_POST[\'priority\'] : $properties[\'priority\']),
                \'changefreq\' => (isset($_POST[\'changefreq\']) ? $_POST[\'changefreq\'] : $properties[\'changefreq\'])
            );
        } else {
            $newProperties = array(
                \'index\' => (isset($_POST[\'index\']) ? $_POST[\'index\'] : $modx->getOption(\'stercseo.index\', null, \'1\')),
                \'follow\' => (isset($_POST[\'follow\']) ? $_POST[\'follow\'] : $modx->getOption(\'stercseo.follow\', null, \'1\')),
                \'sitemap\' => (isset($_POST[\'sitemap\']) ? $_POST[\'sitemap\'] : $modx->getOption(\'stercseo.sitemap\', null, \'1\')),
                \'priority\' => (isset($_POST[\'priority\']) ? $_POST[\'priority\'] : $modx->getOption(\'stercseo.priority\', null, \'0.5\')),
                \'changefreq\' => (isset($_POST[\'changefreq\']) ? $_POST[\'changefreq\'] : $modx->getOption(\'stercseo.changefreq\', null, \'weekly\'))
            );
        }

        // If uri is changed or alias (with freeze uri off) has changed, add a new redirect
        if (($oldResource->get(\'uri\') != $resource->get(\'uri\') ||
                ($oldResource->get(\'uri_override\') == 0 && $oldResource->get(\'alias\') != $resource->get(\'alias\'))) &&
            $oldResource->get(\'uri\') != \'\') {
            $url = urlencode($modx->getOption(\'site_url\').$oldResource->get(\'uri\'));
            if (!$modx->getCount(\'seoUrl\', array(\'url\' => $url))) {
                $data = array(
                    \'url\' => $url,
                    \'resource\' => $resource->get(\'id\'),
                    \'context_key\' => $resource->get(\'context_key\'),
                );
                $redirect = $modx->newObject(\'seoUrl\');
                $redirect->fromArray($data);
                $redirect->save();
            }
            // Recursive set all children resources as redirects
            if ($modx->getOption(\'use_alias_path\')) {
                $resourceOldBasePath = $oldResource->getAliasPath($oldResource->get(\'alias\'), $oldResource->toArray() + array(\'isfolder\' => 1));
                $resourceNewBasePath = $resource->getAliasPath($resource->get(\'alias\'), $resource->toArray() + array(\'isfolder\' => 1));
                $childResources = $modx->getIterator(\'modResource\', array(
                    \'uri:LIKE\' => $resourceOldBasePath . \'%\',
                    \'uri_override\' => \'0\',
                    \'published\' => \'1\',
                    \'deleted\' => \'0\',
                    \'context_key\' => $resource->get(\'context_key\')
                ));
                foreach ($childResources as $childResource) {
                    $url = urlencode($modx->getOption(\'site_url\').$childResource->get(\'uri\'));
                    if (!$modx->getCount(\'seoUrl\', array(\'url\' => $url))) {
                        $data = array(
                            \'url\' => $url,
                            \'resource\' => $childResource->get(\'id\'),
                            \'context_key\' => $resource->get(\'context_key\'),
                        );
                        $redirect = $modx->newObject(\'seoUrl\');
                        $redirect->fromArray($data);
                        $redirect->save();
                    }
                }
            }
        }
        $resource->setProperties($newProperties, \'stercseo\');
        break;

    case \'OnDocFormSave\':
        if (!$stercseo->isAllowed($resource->context_key)) {
            return;
        }

        $url       = urlencode($modx->makeUrl($resource->id, $resource->context_key, \'\', \'full\'));
        $urlExists = $modx->getObject(\'seoUrl\', array(
            \'url\'         => $url,
            \'context_key\' => $resource->context_key
        ));

        if ($urlExists) {
            $modx->removeObject(\'seoUrl\', array(
                \'url\'         => $url,
                \'context_key\' => $resource->context_key
            ));
        }
        break;

    case \'OnLoadWebDocument\':
        if ($modx->resource) {
            if (!$stercseo->isAllowed($modx->resource->get(\'context_key\'))) {
                return;
            }
            $properties = $modx->resource->getProperties(\'stercseo\');
            if (empty($properties)) {
                // Properties not available
                // This means an this resource has nog SEO Tab properties, which means it is a pre-SEO Tab resource
                // Fallback to system defaults
                $properties = array(
                    \'index\' => $modx->getOption(\'stercseo.index\', null, 1),
                    \'follow\' => $modx->getOption(\'stercseo.follow\', null, 1)
                );
            }
            $metaContent = array(\'noodp\', \'noydir\');
            $metaContent[] = (intval($properties[\'index\']) ? \'index\' : \'noindex\');
            $metaContent[] = (intval($properties[\'follow\']) ? \'follow\' : \'nofollow\');

            $modx->setPlaceholder(\'seoTab.robotsTag\', implode(\',\', $metaContent));
        }
        break;

    case \'OnPageNotFound\':
        $options      = array();
        $url          = $modx->getOption(\'server_protocol\').\'://\'.$_SERVER[\'HTTP_HOST\'].$_SERVER[\'REQUEST_URI\'];
        $convertedUrl = urlencode($url);

        $w = array(
            \'url\' => $convertedUrl
        );

        if ($modx->getOption(\'stercseo.context-aware-alias\', null, \'0\')) {
            $w[\'context_key\'] = $modx->context->key;
        }

        $alreadyExists = $modx->getObject(\'seoUrl\', $w);

        if (isset($alreadyExists) && ($modx->context->key !== $alreadyExists->get(\'context_key\'))) {
            $q = $modx->newQuery(\'modContextSetting\');
            $q->where(array(
                \'context_key\' => $alreadyExists->get(\'context_key\'),
                \'key\'         => \'site_url\'
            ));
            $q->prepare();

            $siteUrl = $modx->getObject(\'modContextSetting\', $q);
            if ($siteUrl) {
                $options[\'site_url\'] = $siteUrl->get(\'value\');
            }
        }

        if ($alreadyExists) {
            $url = $modx->makeUrl($alreadyExists->get(\'resource\'), $alreadyExists->get(\'context_key\'), \'\', \'full\', $options);

            $modx->sendRedirect($url, 0, \'REDIRECT_HEADER\', \'HTTP/1.1 301 Moved Permanently\');
        }
        break;

    case \'OnResourceBeforeSort\':
        list($sourceCtx, $resource) = explode(\'_\', $modx->getOption(\'source\', $_POST));
        list($targetCtx, $target) = explode(\'_\', $modx->getOption(\'target\', $_POST));
        switch ($modx->getOption(\'point\', $_POST)) {
            case \'above\':
            case \'below\':
                $tmpRes = $modx->getObject(\'modResource\', $target);
                $target = $tmpRes->get(\'parent\');
                unset($tmpRes);
                break;
        }
        $oldResource = $modx->getObject(\'modResource\', $resource);
        $resource = $modx->getObject(\'modResource\', $resource);
        $resource->set(\'parent\', $target);
        $resource->set(\'uri\', \'\');
        $uriChanged = false;
        if ($oldResource->get(\'uri\') != $resource->get(\'uri\') && $oldResource->get(\'uri\') != \'\') {
            $uriChanged              = true;
        }

        // Recursive set redirects for drag/dropped resource, and its children (where uri_override is not set)
        if ($uriChanged && $modx->getOption(\'use_alias_path\')) {
            $oldResource->set(\'isfolder\', true);
            $resourceOldBasePath = $oldResource->getAliasPath(
                $oldResource->get(\'alias\'),
                $oldResource->toArray()
            );
            $resourceNewBasePath = $resource->getAliasPath(
                $resource->get(\'alias\'),
                $resource->toArray() + array(\'isfolder\' => 1)
            );
            $cond = $modx->newQuery(\'modResource\');
            $cond->where(array(
                array(
                    \'uri:LIKE\'     => $resourceOldBasePath . \'%\',
                    \'OR:id:=\' => $oldResource->id
                ),
                \'uri_override\' => \'0\',
                \'published\' => \'1\',
                \'deleted\' => \'0\',
                \'context_key\' => $resource->get(\'context_key\')
            ));

            $childResources = $modx->getIterator(\'modResource\', $cond);
            foreach ($childResources as $childResource) {
                $url = urlencode($modx->getOption(\'site_url\').$childResource->get(\'uri\'));
                if (!$modx->getCount(\'seoUrl\', array(\'url\' => $url))) {
                    $data = array(
                        \'url\' => $url,
                        \'resource\' => $childResource->get(\'id\'),
                        \'context_key\' => $targetCtx
                    );
                    $redirect = $modx->newObject(\'seoUrl\');
                    $redirect->fromArray($data);
                    $redirect->save();
                }
            }
        }
        break;

    case \'OnResourceDuplicate\':
        if (!$stercseo->isAllowed($newResource->get(\'context_key\'))) {
            return;
        }
        $props = $newResource->getProperties(\'stercseo\');
        $newResource->setProperties($props, \'stercseo\');
        $newResource->save();
        break;

    case \'OnManagerPageBeforeRender\':
        if (!$stercseo->checkUserAccess()) {
            return;
        }
        // If migration status is false, show migrate alert message bar in manager
        if (!$stercseo->redirectMigrationStatus()) {
            $modx->regClientStartupHTMLBlock($stercseo->getChunk(\'migrate/alert\', array(\'message\' => $modx->lexicon(\'stercseo.migrate_alert\'))));
            $modx->regClientCSS($stercseo->config[\'cssUrl\'].\'migrate.css\');
        }
        break;

    case \'OnEmptyTrash\':
        if (count($ids) > 0) {
            foreach ($ids as $id) {
                $modx->removeCollection(\'seoUrl\', array(
                    \'resource\' => $id
                ));
            }
        }
}
return;',
      'locked' => '0',
      'properties' => 'a:0:{}',
      'disabled' => '0',
      'moduleguid' => '',
      'static' => '0',
      'static_file' => '',
    ),
    15 => 
    array (
      'id' => '15',
      'source' => '0',
      'property_preprocess' => '0',
      'name' => 'anchorFix',
      'description' => '',
      'editor_type' => '0',
      'category' => '0',
      'cache_type' => '0',
      'plugincode' => 'if($modx->resource->get(\'id\') != $modx->config[\'site_start\']) {
	$modx->resource->_output = str_replace(\'href="#\',\'href="\' .$modx->makeUrl($modx->resource->get(\'id\')) .\'#\',$modx->resource->_output);
}',
      'locked' => '0',
      'properties' => 'a:0:{}',
      'disabled' => '0',
      'moduleguid' => '',
      'static' => '0',
      'static_file' => '',
    ),
    16 => 
    array (
      'id' => '16',
      'source' => '0',
      'property_preprocess' => '0',
      'name' => 'phpThumbOfCacheManager',
      'description' => 'Handles cache cleaning when clearing the Site Cache.',
      'editor_type' => '0',
      'category' => '25',
      'cache_type' => '0',
      'plugincode' => '/*
 * Handles cache cleanup
 * pThumb
 * Copyright 2013 Jason Grant
 *
 * Please see the GitHub page for documentation or to report bugs:
 * https://github.com/oo12/phpThumbOf
 *
 * pThumb is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation; either version 2 of the License, or (at your option) any
 * later version.
 *
 * pThumb is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * phpThumbOf; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 */

if ($modx->event->name === \'OnSiteRefresh\') {
	if (!$modx->loadClass(\'pThumbCacheCleaner\', MODX_CORE_PATH . \'components/phpthumbof/model/\', true, true)) {
		$modx->log(modX::LOG_LEVEL_ERROR, \'[pThumb] Could not load pThumbCacheCleaner class.\');
		return;
	}
	static $pt_settings = array();
	$pThumb = new pThumbCacheCleaner($modx, $pt_settings, array(), true);
	$pThumb->cleanCache();
}',
      'locked' => '0',
      'properties' => NULL,
      'disabled' => '0',
      'moduleguid' => '',
      'static' => '0',
      'static_file' => '',
    ),
    17 => 
    array (
      'id' => '17',
      'source' => '0',
      'property_preprocess' => '0',
      'name' => 'FileSluggy',
      'description' => 'FileSluggy plugin for MODx Revolution',
      'editor_type' => '0',
      'category' => '29',
      'cache_type' => '0',
      'plugincode' => '/**
 * FileSluggy by Sterc
 * Sanitizes a filename on upload to be a nice and more clean filename, so it will work better with phpthumbof, pthumb and overall cleaner filenames and directories.
 * Copyright 2015 by Sterc
 * FileSluggy is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * FileSluggy is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * formAlicious; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @author Sterc <modx@sterc.nl>
 * @credits:
 *      - Based on the code of the sanitizefilename plugin of Benjamin Vauchel https://github.com/benjamin-vauchel/SanitizeFilename
 *      - The Slug() phunction of AlixAxel https://github.com/alixaxel/phunction/blob/master/phunction/Text.php
 * @version Version 1.3
 * @package filesluggy
 */

$FileSluggy = $modx->getService(
    \'filesluggy\',
    \'FileSluggy\',
    $modx->getOption(
        \'filesluggy.core_path\',
        null,
        $modx->getOption(\'core_path\') . \'components/filesluggy/\'
    )
    .\'model/filesluggy/\',
    $scriptProperties
);
if (!($FileSluggy instanceof FileSluggy)) {
    return;
}
switch ($modx->event->name) {
    case \'OnFileManagerDirCreate\':
    case \'OnFileManagerDirRename\':
        if ($FileSluggy->santizeAllowThisMediaSource($source->get(\'id\'))) {
            if ($modx->getOption(\'filesluggy.sanitizeDir\')) {
                $basePath = $source->getBasePath();
                $dirName  = basename($directory);
                $newDirName  = $FileSluggy->sanitizeName($dirName, true);
                $FileSluggy->renameContainer($source, str_replace(realpath($basePath), \'\', $directory), $newDirName);
                /* Invoke custom system event \'FileSluggyOnUpdateDirname\' */
                $modx->invokeEvent(\'FileSluggyOnUpdateDirname\', array(
                    \'oldName\' => $dirName,
                    \'newName\' => $newDirName
                ));
            }
        }
        break;
    case \'OnFileManagerUpload\':
        $url = parse_url($_SERVER[\'HTTP_REFERER\']);
        $query = $url[\'query\'];
        foreach ($files as $file) {
            if ($FileSluggy->santizeAllowThisMediaSource($source->get(\'id\'))) {
                if (!$source->hasErrors()) {
                    if ($file[\'error\'] == 0) {
                        $basePath = $source->getBasePath();
                        $oldPath  = $directory . $file[\'name\'];
                        if ($FileSluggy->allowType($file[\'name\'])) {
                            $newFileName = $FileSluggy->sanitizeName($file[\'name\']);
                            if ($FileSluggy->checkFileNameChanged()) {
                                $newFileName = $FileSluggy->checkFileExists($basePath . $directory . $newFileName);
                                if ($source->renameObject($oldPath, $newFileName)) {
                                    $modx->invokeEvent(\'FileSluggyOnUpdateFilename\', array(
                                        \'oldName\' => $file[\'name\'],
                                        \'newName\' => $newFileName
                                    ));
                                    return;
                                } else {
                                    return;
                                }
                            } else {
                                return;
                            }
                        } else {
                            return;
                        }
                    } else {
                        return;
                    }
                } else {
                    $modx->log(
                        modX::LOG_LEVEL_ERROR,
                        \'[FileSluggy] There was an error during the upload process...\'
                    );
                }
                return;
            }
            return;
        }
        break;
}',
      'locked' => '0',
      'properties' => 'a:0:{}',
      'disabled' => '0',
      'moduleguid' => '',
      'static' => '0',
      'static_file' => '',
    ),
    18 => 
    array (
      'id' => '18',
      'source' => '0',
      'property_preprocess' => '0',
      'name' => 'Ace',
      'description' => 'Ace code editor plugin for MODx Revolution',
      'editor_type' => '0',
      'category' => '0',
      'cache_type' => '0',
      'plugincode' => '/**
 * Ace Source Editor Plugin
 *
 * Events: OnManagerPageBeforeRender, OnRichTextEditorRegister, OnSnipFormPrerender,
 * OnTempFormPrerender, OnChunkFormPrerender, OnPluginFormPrerender,
 * OnFileCreateFormPrerender, OnFileEditFormPrerender, OnDocFormPrerender
 *
 * @author Danil Kostin <danya.postfactum(at)gmail.com>
 *
 * @package ace
 *
 * @var array $scriptProperties
 * @var Ace $ace
 */
if ($modx->event->name == \'OnRichTextEditorRegister\') {
    $modx->event->output(\'Ace\');
    return;
}

if ($modx->getOption(\'which_element_editor\', null, \'Ace\') !== \'Ace\') {
    return;
}

$ace = $modx->getService(\'ace\', \'Ace\', $modx->getOption(\'ace.core_path\', null, $modx->getOption(\'core_path\').\'components/ace/\').\'model/ace/\');
$ace->initialize();

$extensionMap = array(
    \'tpl\'   => \'text/x-smarty\',
    \'htm\'   => \'text/html\',
    \'html\'  => \'text/html\',
    \'css\'   => \'text/css\',
    \'scss\'  => \'text/x-scss\',
    \'less\'  => \'text/x-less\',
    \'svg\'   => \'image/svg+xml\',
    \'xml\'   => \'application/xml\',
    \'xsl\'   => \'application/xml\',
    \'js\'    => \'application/javascript\',
    \'json\'  => \'application/json\',
    \'php\'   => \'application/x-php\',
    \'sql\'   => \'text/x-sql\',
    \'md\'    => \'text/x-markdown\',
    \'txt\'   => \'text/plain\',
    \'twig\'  => \'text/x-twig\'
);

// Defines wether we should highlight modx tags
$modxTags = false;
switch ($modx->event->name) {
    case \'OnSnipFormPrerender\':
        $field = \'modx-snippet-snippet\';
        $mimeType = \'application/x-php\';
        break;
    case \'OnTempFormPrerender\':
        $field = \'modx-template-content\';
        $modxTags = true;

        switch (true) {
            case $modx->getOption(\'twiggy_class\'):
                $mimeType = \'text/x-twig\';
                break;
            case $modx->getOption(\'pdotools_fenom_parser\'):
                $mimeType = \'text/x-smarty\';
                break;
            default:
                $mimeType = \'text/html\';
                break;
        }

        break;
    case \'OnChunkFormPrerender\':
        $field = \'modx-chunk-snippet\';
        if ($modx->controller->chunk && $modx->controller->chunk->isStatic()) {
            $extension = pathinfo($modx->controller->chunk->getSourceFile(), PATHINFO_EXTENSION);
            $mimeType = isset($extensionMap[$extension]) ? $extensionMap[$extension] : \'text/plain\';
        } else {
            $mimeType = \'text/html\';
        }
        $modxTags = true;

        switch (true) {
            case $modx->getOption(\'twiggy_class\'):
                $mimeType = \'text/x-twig\';
                break;
            case $modx->getOption(\'pdotools_fenom_default\'):
                $mimeType = \'text/x-smarty\';
                break;
            default:
                $mimeType = \'text/html\';
                break;
        }

        break;
    case \'OnPluginFormPrerender\':
        $field = \'modx-plugin-plugincode\';
        $mimeType = \'application/x-php\';
        break;
    case \'OnFileCreateFormPrerender\':
        $field = \'modx-file-content\';
        $mimeType = \'text/plain\';
        break;
    case \'OnFileEditFormPrerender\':
        $field = \'modx-file-content\';
        $extension = pathinfo($scriptProperties[\'file\'], PATHINFO_EXTENSION);
        $mimeType = isset($extensionMap[$extension])
            ? $extensionMap[$extension]
            : \'text/plain\';
        $modxTags = $extension == \'tpl\';
        break;
    case \'OnDocFormPrerender\':
        if (!$modx->controller->resourceArray) {
            return;
        }
        $field = \'ta\';
        $mimeType = $modx->getObject(\'modContentType\', $modx->controller->resourceArray[\'content_type\'])->get(\'mime_type\');

        switch (true) {
            case $mimeType == \'text/html\' && $modx->getOption(\'twiggy_class\'):
                $mimeType = \'text/x-twig\';
                break;
            case $mimeType == \'text/html\' && $modx->getOption(\'pdotools_fenom_parser\'):
                $mimeType = \'text/x-smarty\';
                break;
        }

        if ($modx->getOption(\'use_editor\')){
            $richText = $modx->controller->resourceArray[\'richtext\'];
            $classKey = $modx->controller->resourceArray[\'class_key\'];
            if ($richText || in_array($classKey, array(\'modStaticResource\',\'modSymLink\',\'modWebLink\',\'modXMLRPCResource\'))) {
                $field = false;
            }
        }
        $modxTags = true;
        break;
    default:
        return;
}

$modxTags = (int) $modxTags;
$script = \'\';
if ($field) {
    $script .= "MODx.ux.Ace.replaceComponent(\'$field\', \'$mimeType\', $modxTags);";
}

if ($modx->event->name == \'OnDocFormPrerender\' && !$modx->getOption(\'use_editor\')) {
    $script .= "MODx.ux.Ace.replaceTextAreas(Ext.query(\'.modx-richtext\'));";
}

if ($script) {
    $modx->controller->addHtml(\'<script>Ext.onReady(function() {\' . $script . \'});</script>\');
}',
      'locked' => '0',
      'properties' => 'a:0:{}',
      'disabled' => '0',
      'moduleguid' => '',
      'static' => '0',
      'static_file' => 'ace/elements/plugins/ace.plugin.php',
    ),
    19 => 
    array (
      'id' => '19',
      'source' => '0',
      'property_preprocess' => '0',
      'name' => 'seoPro',
      'description' => 'seoPro 1.0.4-pl . SEO optimizing plugin for MODx Revolution',
      'editor_type' => '0',
      'category' => '0',
      'cache_type' => '0',
      'plugincode' => '/**
 * The base seoPro snippet.
 *
 * @package seopro
 */
$seoPro = $modx->getService(\'seopro\', \'seoPro\', $modx->getOption(\'seopro.core_path\', null, $modx->getOption(\'core_path\') . \'components/seopro/\') . \'model/seopro/\', $scriptProperties);
if (!($seoPro instanceof seoPro))
  return \'\';

$disabledTemplates = explode(\',\', $modx->getOption(\'seopro.disabledtemplates\', null, \'0\'));

switch ($modx->event->name) {
  case \'OnDocFormRender\':
    $template = ($resource->get(\'template\')) ? (string)$resource->get(\'template\') : (string)$_REQUEST[\'template\'];
    if (in_array($template, $disabledTemplates)) {
      break;
    }
    $currClassKey = $resource->get(\'class_key\');
    $strFields = $modx->getOption(\'seopro.fields\', null, \'pagetitle:70,longtitle:70,description:155,alias:2023,menutitle:2023\');
    $arrFields = array();
    if (is_array(explode(\',\', $strFields))) {
      foreach (explode(\',\', $strFields) as $field) {
        list($fieldName, $fieldCount) = explode(\':\', $field);
        $arrFields[$fieldName] = $fieldCount;
      }
    } else {
      return \'\';
    }

    $keywords = \'\';
    $modx->controller->addLexiconTopic(\'seopro:default\');
    if ($mode == \'upd\') {
      $url = $modx->makeUrl($resource->get(\'id\'), \'\', \'\', \'full\');
      $url = str_replace($resource->get(\'alias\'), \'<span id=\\"seopro-replace-alias\\">\' . $resource->get(\'alias\') . \'</span>\', $url);
      $seoKeywords = $modx->getObject(\'seoKeywords\', array(\'resource\' => $resource->get(\'id\')));
      if ($seoKeywords) {
        $keywords = $seoKeywords->get(\'keywords\');
      }
    } else {
      if ($_REQUEST[\'id\']) {
        $url = $modx->makeUrl($_REQUEST[\'id\'], \'\', \'\', \'full\');
        $url .= \'/<span id=\\"seopro-replace-alias\\"></span>\';
      } else {
        $url = $modx->getOption(\'site_url\') . \'<span id=\\"seopro-replace-alias\\"></span>\';
      }
    }

    if ($_REQUEST[\'id\'] == $modx->getOption(\'site_start\')) {
      unset($arrFields[\'alias\']);
      unset($arrFields[\'menutitle\']);
    }


    $config = $seoPro->config;
    unset($config[\'resource\']);
    $modx->regClientStartupHTMLBlock(\'<script type="text/javascript">
		Ext.onReady(function() {
			seoPro.config = \' . $modx->toJSON($config) . \';
			seoPro.config.record = "\' . $keywords . \'";
			seoPro.config.values = {};
			seoPro.config.fields = "\' . implode(",", array_keys($arrFields)) . \'";
			seoPro.config.chars = \' . $modx->toJSON($arrFields) . \'
			seoPro.config.url = "\' . $url . \'";
		});
	</script>\');

    /* include CSS and JS*/
    $version = $modx->getVersionData();
    if($version[\'version\'] == 2 && $version[\'major_version\'] == 2){
     $modx->regClientCSS($seoPro->config[\'assetsUrl\'] . \'css/mgr.css\');
    }else{
     $modx->regClientCSS($seoPro->config[\'assetsUrl\'] . \'css/mgr23.css\');
    }
    $modx->regClientStartupScript($seoPro->config[\'assetsUrl\'] . \'js/mgr/seopro.js??v=\' . $modx->getOption(\'seopro.version\', null, \'v1.0.0\'));
    $modx->regClientStartupScript($seoPro->config[\'assetsUrl\'] . \'js/mgr/resource.js?v=\' . $modx->getOption(\'seopro.version\', null, \'v1.0.0\'));

    break;

  case \'OnDocFormSave\':
    $template = ($resource->get(\'template\')) ? (string)$resource->get(\'template\') : (string)$_REQUEST[\'template\'];
    if (in_array($template, $disabledTemplates)) {
      break;
    }
    $seoKeywords = $modx->getObject(\'seoKeywords\', array(\'resource\' => $resource->get(\'id\')));
    if (!$seoKeywords && isset($resource)) {
      $seoKeywords = $modx->newObject(\'seoKeywords\', array(\'resource\' => $resource->get(\'id\')));
    }
    if($seoKeywords){
      $seoKeywords->set(\'keywords\', trim($_POST[\'keywords\'], \',\'));
      $seoKeywords->save();
    }
    break;

  case \'onResourceDuplicate\':
    $template = ($resource->get(\'template\')) ? (string)$resource->get(\'template\') : (string)$_REQUEST[\'template\'];
    if (in_array($template, $disabledTemplates)) {
      break;
    }
    $seoKeywords = $modx->getObject(\'seoKeywords\', array(\'resource\' => $resource->get(\'id\')));
    if (!$seoKeywords) {
      $seoKeywords = $modx->newObject(\'seoKeywords\', array(\'resource\' => $resource->get(\'id\')));
    }
    $newSeoKeywords = $modx->newObject(\'seoKeywords\');
    $newSeoKeywords->fromArray($seoKeywords->toArray());
    $newSeoKeywords->set(\'resource\', $newResource->get(\'id\'));
    $newSeoKeywords->save();
    break;

  case \'OnLoadWebDocument\':
    if ($modx->context->get(\'key\') == "mgr") {
      break;
    }
    $template = ($modx->resource->get(\'template\')) ? (string)$modx->resource->get(\'template\') : (string)$_REQUEST[\'template\'];
    if (in_array($template, $disabledTemplates)) {
      break;
    }
    $seoKeywords = $modx->getObject(\'seoKeywords\', array(\'resource\' => $modx->resource->get(\'id\')));
    if ($seoKeywords) {
      $keyWords = $seoKeywords->get(\'keywords\');
      $modx->setPlaceholder(\'seoPro.keywords\', $keyWords);
    }
    $siteBranding = (boolean) $modx->getOption(\'seopro.allowbranding\', null, true);
    $siteDelimiter = $modx->getOption(\'seopro.delimiter\', null, \'/\');
    $siteUseSitename = (boolean) $modx->getOption(\'seopro.usesitename\', null, true);
    $siteID = $modx->resource->get(\'id\');
    $siteName = $modx->getOption(\'site_name\');
    $longtitle = $modx->resource->get(\'longtitle\');
    $pagetitle = $modx->resource->get(\'pagetitle\');
    $seoProTitle = array();
    if ($siteID == $modx->getOption(\'site_start\')) {
      $seoProTitle[\'pagetitle\'] = !empty($longtitle) ? $longtitle : $siteName;
    } else {
      $seoProTitle[\'pagetitle\'] = !empty($longtitle) ? $longtitle : $pagetitle;
      if ($siteUseSitename) {
        $seoProTitle[\'delimiter\'] = $siteDelimiter;
        $seoProTitle[\'sitename\'] = $siteName;
      }
    }
    $modx->setPlaceholder(\'seoPro.title\', implode(" ", $seoProTitle));
    if ($siteBranding) {
      $modx->regClientStartupHTMLBlock(\'<!-- This site is optimized with the Sterc seoPro plugin \' . $modx->getOption(\'seopro.version\', null, \'v1.0.0\') . \' - http://www.sterc.nl/modx/seopro -->\');
    }
    break;
}',
      'locked' => '0',
      'properties' => NULL,
      'disabled' => '0',
      'moduleguid' => '',
      'static' => '0',
      'static_file' => '',
    ),
    23 => 
    array (
      'id' => '23',
      'source' => '1',
      'property_preprocess' => '0',
      'name' => 'modDevTools',
      'description' => '',
      'editor_type' => '0',
      'category' => '34',
      'cache_type' => '0',
      'plugincode' => '/**
 * modDevTools
 *
 * Copyright 2014 by Vitaly Kireev <kireevvit@gmail.com>
 *
 * @package moddevtools
 *
 * @var modX $modx
 * @var int $id
 * @var string $mode
 */

/**
 * @var modx $modx
 */
$path = $modx->getOption(\'moddevtools_core_path\',null,$modx->getOption(\'core_path\').\'components/moddevtools/\').\'model/moddevtools/\';
/**
 * @var modDevTools $devTools
 */
$devTools = $modx->getService(\'devTools\',\'modDevTools\',$path, array(\'debug\' => false));
$eventName = $modx->event->name;

/**
 * Usergroup check. Only show modDevTools for specific usergroups
 * Uses comma separated plugin property to specify the groups
 */
if($usergroups) {
    $usergroups = explode(\',\', $usergroups);
    array_walk($usergroups, \'trim\');
    if(!$modx->user->isMember($usergroups)) {
        return;
    }
}

switch($eventName) {
    case \'OnDocFormSave\':
        $devTools->debug(\'Start OnDocFormSave\');
        $devTools->parseContent($resource);
        break;
    case \'OnTempFormSave\':
        $devTools->debug(\'Start OnTempFormSave\');
        $devTools->parseContent($template);
        break;
    case \'OnTVFormSave\':

        break;
    case \'OnChunkFormSave\':
        $devTools->debug(\'Start OnChunkFormSave\');
        $devTools->parseContent($chunk);
        break;
    case \'OnSnipFormSave\':

        break;
    /* Add tabs */
    case \'OnDocFormPrerender\':
        if ($modx->event->name == \'OnDocFormPrerender\') {
            $devTools->getBreadCrumbs($scriptProperties);
            return;
        }
        break;

    case \'OnTempFormPrerender\':
        if ($mode == modSystemEvent::MODE_UPD) {
            $result = $devTools->outputTab(\'Template\');
        }
        break;

    case \'OnTVFormPrerender\':
        break;


    case \'OnChunkFormPrerender\':
        if ($mode == modSystemEvent::MODE_UPD) {
            $result = $devTools->outputTab(\'Chunk\');
        }
        break;

    case \'OnSnipFormPrerender\':
        if ($mode == modSystemEvent::MODE_UPD) {
            $result = $devTools->outputTab(\'Snippet\');
        }
        break;


}

if (isset($result) && $result === true)
    return;
elseif (isset($result)) {
    $modx->log(modX::LOG_LEVEL_ERROR,\'[modDevTools] An error occured. Event: \'.$eventName.\' - Error: \'.($result === false) ? \'undefined error\' : $result);
    return;
}',
      'locked' => '0',
      'properties' => 'a:1:{s:10:"usergroups";a:7:{s:4:"name";s:10:"usergroups";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:20:"Administrator,Client";s:7:"lexicon";N;s:4:"area";s:0:"";}}',
      'disabled' => '0',
      'moduleguid' => '',
      'static' => '0',
      'static_file' => 'core/components/moddevtools/elements/plugins/plugin.moddevtools.php',
    ),
    25 => 
    array (
      'id' => '25',
      'source' => '0',
      'property_preprocess' => '0',
      'name' => 'Collections',
      'description' => '',
      'editor_type' => '0',
      'category' => '36',
      'cache_type' => '0',
      'plugincode' => '/**
 * Collections
 *
 * DESCRIPTION
 *
 * This plugin inject JS to handle proper working of close buttons in Resource\'s panel (OnDocFormPrerender)
 * This plugin handles setting proper show_in_tree parameter (OnBeforeDocFormSave, OnResourceSort)
 *
 * @var modX $modx
 * @var array $scriptProperties
 */
$corePath = $modx->getOption(\'collections.core_path\', null, $modx->getOption(\'core_path\', null, MODX_CORE_PATH) . \'components/collections/\');
/** @var Collections $collections */
$collections = $modx->getService(
    \'collections\',
    \'Collections\',
    $corePath . \'model/collections/\',
    array(
        \'core_path\' => $corePath
    )
);

$className = \'Collections\' . $modx->event->name;

$modx->loadClass(\'CollectionsPlugin\', $collections->getOption(\'modelPath\') . \'collections/events/\', true, true);
$modx->loadClass($className, $collections->getOption(\'modelPath\') . \'collections/events/\', true, true);

if (class_exists($className)) {
    /** @var CollectionsPlugin $handler */
    $handler = new $className($modx, $scriptProperties);
    $handler->run();
}

return;',
      'locked' => '0',
      'properties' => 'a:0:{}',
      'disabled' => '0',
      'moduleguid' => '',
      'static' => '0',
      'static_file' => '',
    ),
    29 => 
    array (
      'id' => '29',
      'source' => '0',
      'property_preprocess' => '0',
      'name' => 'TinyMCERTE',
      'description' => '',
      'editor_type' => '0',
      'category' => '39',
      'cache_type' => '0',
      'plugincode' => '/**
 * TinyMCE Rich Tech Editor
 *
 */
$corePath = $modx->getOption(\'tinymcerte.core_path\', null, $modx->getOption(\'core_path\', null, MODX_CORE_PATH) . \'components/tinymcerte/\');
/** @var TinyMCERTE $tinymcerte */
$tinymcerte = $modx->getService(
    \'tinymcerte\',
    \'TinyMCERTE\',
    $corePath . \'model/tinymcerte/\',
    array(
        \'core_path\' => $corePath
    )
);

$className = \'TinyMCERTE\' . $modx->event->name;
$modx->loadClass(\'TinyMCERTEPlugin\', $tinymcerte->getOption(\'modelPath\') . \'tinymcerte/events/\', true, true);
$modx->loadClass($className, $tinymcerte->getOption(\'modelPath\') . \'tinymcerte/events/\', true, true);
if (class_exists($className)) {
    /** @var TinyMCERTEPlugin $handler */
    $handler = new $className($modx, $scriptProperties);
    $handler->run();
}
return;',
      'locked' => '0',
      'properties' => 'a:0:{}',
      'disabled' => '0',
      'moduleguid' => '',
      'static' => '0',
      'static_file' => '',
    ),
    30 => 
    array (
      'id' => '30',
      'source' => '0',
      'property_preprocess' => '0',
      'name' => 'modxMinifyCacheClear',
      'description' => '',
      'editor_type' => '0',
      'category' => '41',
      'cache_type' => '0',
      'plugincode' => 'switch ($modx->event->name) {

	case \'OnSiteRefresh\':
		$modxminify = $modx->getService(\'modxminify\',\'modxMinify\',$modx->getOption(\'modxminify.core_path\',null,$modx->getOption(\'core_path\').\'components/modxminify/\').\'model/modxminify/\',$scriptProperties);
		if (!($modxminify instanceof modxMinify)) return \'\';
		$modxminify->emptyMinifyCacheAll();
		break;

}',
      'locked' => '0',
      'properties' => 'a:0:{}',
      'disabled' => '0',
      'moduleguid' => '',
      'static' => '0',
      'static_file' => '',
    ),
    31 => 
    array (
      'id' => '31',
      'source' => '1',
      'property_preprocess' => '0',
      'name' => 'pdoTools',
      'description' => '',
      'editor_type' => '0',
      'category' => '31',
      'cache_type' => '0',
      'plugincode' => '/** @var modX $modx */
switch ($modx->event->name) {

    case \'OnMODXInit\':
        $fqn = $modx->getOption(\'pdoTools.class\', null, \'pdotools.pdotools\', true);
        $path = $modx->getOption(\'pdotools_class_path\', null, MODX_CORE_PATH . \'components/pdotools/model/\', true);
        $modx->loadClass($fqn, $path, false, true);

        $fqn = $modx->getOption(\'pdoFetch.class\', null, \'pdotools.pdofetch\', true);
        $path = $modx->getOption(\'pdofetch_class_path\', null, MODX_CORE_PATH . \'components/pdotools/model/\', true);
        $modx->loadClass($fqn, $path, false, true);
        break;

    case \'OnBeforeSaveWebPageCache\':
        if (!empty($modx->config[\'fenom_jscripts\'])) {
            foreach ($modx->config[\'fenom_jscripts\'] as $key => $value) {
                unset($modx->resource->_jscripts[$key]);
            }
            $modx->resource->_jscripts = array_values($modx->resource->_jscripts);
        }
        if (!empty($modx->config[\'fenom_sjscripts\'])) {
            foreach ($modx->config[\'fenom_sjscripts\'] as $key => $value) {
                unset($modx->resource->_sjscripts[$key]);
            }
            $modx->resource->_sjscripts = array_values($modx->resource->_sjscripts);
        }
        if (!empty($modx->config[\'fenom_loadedscripts\'])) {
            foreach ($modx->config[\'fenom_loadedscripts\'] as $key => $value) {
                unset($modx->resource->_loadedjscripts[$key]);
            }
        }
        break;

    case \'OnSiteRefresh\':
        /** @var pdoTools $pdoTools */
        if ($pdoTools = $modx->getService(\'pdoTools\')) {
            if ($pdoTools->clearFileCache()) {
                $modx->log(modX::LOG_LEVEL_INFO, $modx->lexicon(\'refresh_default\') . \': pdoTools\');
            }
        }
        break;
}',
      'locked' => '0',
      'properties' => NULL,
      'disabled' => '0',
      'moduleguid' => '',
      'static' => '0',
      'static_file' => 'core/components/pdotools/elements/plugins/plugin.pdotools.php',
    ),
    32 => 
    array (
      'id' => '32',
      'source' => '1',
      'property_preprocess' => '0',
      'name' => 'debugParser',
      'description' => '',
      'editor_type' => '0',
      'category' => '42',
      'cache_type' => '0',
      'plugincode' => 'if (empty($_REQUEST[\'debug\']) || !$modx->user->hasSessionContext(\'mgr\') || $modx->context->key == \'mgr\') {
	return;
}

switch ($modx->event->name) {

	case \'OnHandleRequest\':
		if ($modx->parser instanceof pdoParser && $modx->loadClass(\'debugPdoParser\', MODX_CORE_PATH . \'components/debugparser/model/\', false, true)) {
			$modx->parser = new debugPdoParser($modx);
		}
		elseif ($modx->loadClass(\'debugParser\', MODX_CORE_PATH . \'components/debugparser/model/\', false, true)) {
			$modx->parser = new debugParser($modx);
		}
		break;

	case \'OnWebPageInit\':
		if (method_exists($modx->parser, \'clearCache\') && empty($_REQUEST[\'cache\'])) {
			$modx->parser->clearCache();
		}
		break;

	case \'OnLoadWebPageCache\':
		if (property_exists($modx->parser, \'from_cache\')) {
			$modx->parser->from_cache = true;
		}
		break;

	case \'OnWebPagePrerender\':
		if (method_exists($modx->parser, \'generateReport\')) {
			$modx->parser->generateReport();
		}
		break;
}',
      'locked' => '0',
      'properties' => NULL,
      'disabled' => '0',
      'moduleguid' => '',
      'static' => '0',
      'static_file' => 'core/components/debugparser/elements/plugins/plugin.debugparser.php',
    ),
    33 => 
    array (
      'id' => '33',
      'source' => '2',
      'property_preprocess' => '0',
      'name' => 'Narrowcasting',
      'description' => '',
      'editor_type' => '0',
      'category' => '48',
      'cache_type' => '0',
      'plugincode' => '/**
	 * Narrowcasting
	 *
	 * Copyright 2016 by Oene Tjeerd de Bruin <oenetjeerd@sterc.nl>
	 *
	 * Narrowcasting is free software; you can redistribute it and/or modify it under
	 * the terms of the GNU General Public License as published by the Free Software
	 * Foundation; either version 2 of the License, or (at your option) any later
	 * version.
	 *
	 * Narrowcasting is distributed in the hope that it will be useful, but WITHOUT ANY
	 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
	 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
	 *
	 * You should have received a copy of the GNU General Public License along with
	 * Narrowcasting; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
	 * Suite 330, Boston, MA 02111-1307 USA
	 */

	switch($modx->event->name) {
		case \'OnLoadWebDocument\':
			if ($modx->loadClass(\'Narrowcasting\', $modx->getOption(\'narrowcasting.core_path\', null, $modx->getOption(\'core_path\').\'components/narrowcasting/\').\'model/narrowcasting/\', true, true)) {
                $narrowcasting = new Narrowcasting($modx);

        	    if ($narrowcasting instanceOf Narrowcasting) {
        	        $narrowcasting->initializePlayer();
        	    }
			}
	        
	        break;
	}',
      'locked' => '0',
      'properties' => 'a:0:{}',
      'disabled' => '0',
      'moduleguid' => '',
      'static' => '0',
      'static_file' => '',
    ),
  ),
  'policies' => 
  array (
    'modAccessContext' => 
    array (
      'mgr' => 
      array (
        0 => 
        array (
          'principal' => 1,
          'authority' => 0,
          'policy' => 
          array (
            'about' => true,
            'access_permissions' => true,
            'actions' => true,
            'change_password' => true,
            'change_profile' => true,
            'charsets' => true,
            'class_map' => true,
            'components' => true,
            'content_types' => true,
            'countries' => true,
            'create' => true,
            'credits' => true,
            'customize_forms' => true,
            'dashboards' => true,
            'database' => true,
            'database_truncate' => true,
            'delete_category' => true,
            'delete_chunk' => true,
            'delete_context' => true,
            'delete_document' => true,
            'delete_eventlog' => true,
            'delete_plugin' => true,
            'delete_propertyset' => true,
            'delete_role' => true,
            'delete_snippet' => true,
            'delete_template' => true,
            'delete_tv' => true,
            'delete_user' => true,
            'directory_chmod' => true,
            'directory_create' => true,
            'directory_list' => true,
            'directory_remove' => true,
            'directory_update' => true,
            'edit_category' => true,
            'edit_chunk' => true,
            'edit_context' => true,
            'edit_document' => true,
            'edit_locked' => true,
            'edit_plugin' => true,
            'edit_propertyset' => true,
            'edit_role' => true,
            'edit_snippet' => true,
            'edit_template' => true,
            'edit_tv' => true,
            'edit_user' => true,
            'element_tree' => true,
            'empty_cache' => true,
            'error_log_erase' => true,
            'error_log_view' => true,
            'export_static' => true,
            'file_create' => true,
            'file_list' => true,
            'file_manager' => true,
            'file_remove' => true,
            'file_tree' => true,
            'file_update' => true,
            'file_upload' => true,
            'file_unpack' => true,
            'file_view' => true,
            'flush_sessions' => true,
            'frames' => true,
            'help' => true,
            'home' => true,
            'import_static' => true,
            'languages' => true,
            'lexicons' => true,
            'list' => true,
            'load' => true,
            'logout' => true,
            'logs' => true,
            'menus' => true,
            'menu_reports' => true,
            'menu_security' => true,
            'menu_site' => true,
            'menu_support' => true,
            'menu_system' => true,
            'menu_tools' => true,
            'menu_user' => true,
            'messages' => true,
            'namespaces' => true,
            'new_category' => true,
            'new_chunk' => true,
            'new_context' => true,
            'new_document' => true,
            'new_document_in_root' => true,
            'new_plugin' => true,
            'new_propertyset' => true,
            'new_role' => true,
            'new_snippet' => true,
            'new_static_resource' => true,
            'new_symlink' => true,
            'new_template' => true,
            'new_tv' => true,
            'new_user' => true,
            'new_weblink' => true,
            'packages' => true,
            'policy_delete' => true,
            'policy_edit' => true,
            'policy_new' => true,
            'policy_save' => true,
            'policy_template_delete' => true,
            'policy_template_edit' => true,
            'policy_template_new' => true,
            'policy_template_save' => true,
            'policy_template_view' => true,
            'policy_view' => true,
            'property_sets' => true,
            'providers' => true,
            'publish_document' => true,
            'purge_deleted' => true,
            'remove' => true,
            'remove_locks' => true,
            'resource_duplicate' => true,
            'resourcegroup_delete' => true,
            'resourcegroup_edit' => true,
            'resourcegroup_new' => true,
            'resourcegroup_resource_edit' => true,
            'resourcegroup_resource_list' => true,
            'resourcegroup_save' => true,
            'resourcegroup_view' => true,
            'resource_quick_create' => true,
            'resource_quick_update' => true,
            'resource_tree' => true,
            'save' => true,
            'save_category' => true,
            'save_chunk' => true,
            'save_context' => true,
            'save_document' => true,
            'save_plugin' => true,
            'save_propertyset' => true,
            'save_role' => true,
            'save_snippet' => true,
            'save_template' => true,
            'save_tv' => true,
            'save_user' => true,
            'search' => true,
            'settings' => true,
            'sources' => true,
            'source_delete' => true,
            'source_edit' => true,
            'source_save' => true,
            'source_view' => true,
            'steal_locks' => true,
            'tree_show_element_ids' => true,
            'tree_show_resource_ids' => true,
            'undelete_document' => true,
            'unlock_element_properties' => true,
            'unpublish_document' => true,
            'usergroup_delete' => true,
            'usergroup_edit' => true,
            'usergroup_new' => true,
            'usergroup_save' => true,
            'usergroup_user_edit' => true,
            'usergroup_user_list' => true,
            'usergroup_view' => true,
            'view' => true,
            'view_category' => true,
            'view_chunk' => true,
            'view_context' => true,
            'view_document' => true,
            'view_element' => true,
            'view_eventlog' => true,
            'view_offline' => true,
            'view_plugin' => true,
            'view_propertyset' => true,
            'view_role' => true,
            'view_snippet' => true,
            'view_sysinfo' => true,
            'view_template' => true,
            'view_tv' => true,
            'view_unpublished' => true,
            'view_user' => true,
            'workspaces' => true,
          ),
        ),
        1 => 
        array (
          'principal' => 2,
          'authority' => 9999,
          'policy' => 
          array (
            'about' => false,
            'access_permissions' => false,
            'actions' => false,
            'change_password' => true,
            'change_profile' => true,
            'charsets' => false,
            'class_map' => true,
            'components' => true,
            'content_types' => false,
            'countries' => true,
            'create' => true,
            'credits' => false,
            'customize_forms' => false,
            'dashboards' => false,
            'database' => true,
            'database_truncate' => false,
            'delete_category' => false,
            'delete_chunk' => false,
            'delete_context' => false,
            'delete_document' => true,
            'delete_eventlog' => false,
            'delete_plugin' => false,
            'delete_propertyset' => false,
            'delete_role' => false,
            'delete_snippet' => false,
            'delete_template' => false,
            'delete_tv' => false,
            'delete_user' => true,
            'directory_chmod' => true,
            'directory_create' => true,
            'directory_list' => true,
            'directory_remove' => true,
            'directory_update' => true,
            'edit_category' => false,
            'edit_chunk' => false,
            'edit_context' => false,
            'edit_document' => true,
            'edit_locked' => false,
            'edit_plugin' => false,
            'edit_propertyset' => false,
            'edit_role' => false,
            'edit_snippet' => false,
            'edit_template' => false,
            'edit_tv' => false,
            'edit_user' => true,
            'element_tree' => false,
            'empty_cache' => true,
            'error_log_erase' => false,
            'error_log_view' => false,
            'events' => false,
            'export_static' => false,
            'file_create' => false,
            'file_list' => true,
            'file_manager' => true,
            'file_remove' => true,
            'file_tree' => true,
            'file_unpack' => false,
            'file_update' => true,
            'file_upload' => true,
            'file_view' => true,
            'flush_sessions' => false,
            'frames' => true,
            'help' => false,
            'home' => true,
            'import_static' => false,
            'languages' => false,
            'lexicons' => false,
            'list' => true,
            'load' => true,
            'logout' => true,
            'logs' => false,
            'menu_reports' => false,
            'menu_security' => false,
            'menu_site' => true,
            'menu_support' => false,
            'menu_system' => false,
            'menu_tools' => true,
            'menu_user' => true,
            'menus' => false,
            'messages' => false,
            'namespaces' => false,
            'new_category' => false,
            'new_chunk' => false,
            'new_context' => false,
            'new_document' => true,
            'new_document_in_root' => true,
            'new_plugin' => false,
            'new_propertyset' => false,
            'new_role' => false,
            'new_snippet' => false,
            'new_static_resource' => false,
            'new_symlink' => true,
            'new_template' => false,
            'new_tv' => false,
            'new_user' => true,
            'new_weblink' => true,
            'packages' => false,
            'policy_delete' => false,
            'policy_edit' => false,
            'policy_new' => false,
            'policy_save' => false,
            'policy_template_delete' => false,
            'policy_template_edit' => false,
            'policy_template_new' => false,
            'policy_template_save' => false,
            'policy_template_view' => false,
            'policy_view' => false,
            'property_sets' => false,
            'providers' => false,
            'publish_document' => true,
            'purge_deleted' => true,
            'remove' => true,
            'remove_locks' => false,
            'resource_duplicate' => true,
            'resource_quick_create' => true,
            'resource_quick_update' => true,
            'resource_tree' => true,
            'resourcegroup_delete' => false,
            'resourcegroup_edit' => false,
            'resourcegroup_new' => false,
            'resourcegroup_resource_edit' => true,
            'resourcegroup_resource_list' => true,
            'resourcegroup_save' => false,
            'resourcegroup_view' => false,
            'save' => true,
            'save_category' => false,
            'save_chunk' => false,
            'save_context' => false,
            'save_document' => true,
            'save_plugin' => true,
            'save_propertyset' => false,
            'save_role' => false,
            'save_snippet' => false,
            'save_template' => false,
            'save_tv' => false,
            'save_user' => false,
            'search' => true,
            'settings' => false,
            'source_delete' => false,
            'source_edit' => false,
            'source_save' => false,
            'source_view' => true,
            'sources' => false,
            'steal_locks' => false,
            'tree_show_element_ids' => false,
            'tree_show_resource_ids' => true,
            'undelete_document' => true,
            'unlock_element_properties' => false,
            'unpublish_document' => true,
            'usergroup_delete' => false,
            'usergroup_edit' => false,
            'usergroup_new' => false,
            'usergroup_save' => false,
            'usergroup_user_edit' => true,
            'usergroup_user_list' => false,
            'usergroup_view' => true,
            'view' => true,
            'view_category' => false,
            'view_chunk' => true,
            'view_context' => true,
            'view_document' => true,
            'view_element' => false,
            'view_eventlog' => false,
            'view_offline' => true,
            'view_plugin' => false,
            'view_propertyset' => false,
            'view_role' => true,
            'view_snippet' => false,
            'view_sysinfo' => false,
            'view_template' => true,
            'view_tv' => true,
            'view_unpublished' => true,
            'view_user' => true,
            'workspaces' => false,
          ),
        ),
      ),
    ),
  ),
);