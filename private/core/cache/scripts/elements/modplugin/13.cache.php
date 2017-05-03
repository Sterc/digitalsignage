<?php  return 'if (isset($_GET[\'a\'])) {
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
}
return;
';