<?php
/**
 * Loads the home page.
 *
 * @package usermanagement
 * @subpackage controllers
 */
class userManagementHomeManagerController extends userManagementBaseManagerController {
    public function process(array $scriptProperties = array()) {

    }
    public function getPageTitle() { return $this->modx->lexicon('usermanagement'); }
    public function loadCustomCssJs() {
        $this->addJavascript($this->usermanagement->config['jsUrl'].'mgr/widgets/users.grid.js');
        $this->addJavascript($this->usermanagement->config['jsUrl'].'mgr/widgets/home.panel.js');
        $this->addLastJavascript($this->usermanagement->config['jsUrl'].'mgr/sections/home.js');
    }
    public function getTemplateFile() { return $this->usermanagement->config['templatesPath'].'home.tpl'; }
}