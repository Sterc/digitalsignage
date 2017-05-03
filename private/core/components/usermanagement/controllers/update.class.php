<?php
/**
 * Loads the update page.
 *
 * @package usermanagement
 * @subpackage controllers
 */
class userManagementUpdateManagerController extends userManagementBaseManagerController {
    public function process(array $scriptProperties = array()) {

    }
    public function getPageTitle() { return $this->modx->lexicon('usermanagement'); }
    public function loadCustomCssJs() {
        $this->addJavascript($this->usermanagement->config['jsUrl'].'mgr/widgets/update.panel.js');
        $this->addJavascript($this->usermanagement->config['jsUrl'].'mgr/widgets/usergroups.grid.js');
        $this->addLastJavascript($this->usermanagement->config['jsUrl'].'mgr/sections/update.js');
    }
    public function getTemplateFile() { return $this->usermanagement->config['templatesPath'].'update.tpl'; }
}