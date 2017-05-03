<?php
class StatusOverrideIPsHomeManagerController extends StatusOverrideIPsManagerController {
    public function process(array $scriptProperties = array()) {}
    public function getPageTitle() { return $this->modx->lexicon('statusoverrideips'); }
    public function loadCustomCssJs() {
        $this->addJavascript($this->statusoverrideips->config['jsUrl'].'mgr/widgets/ips.grid.js');
        $this->addJavascript($this->statusoverrideips->config['jsUrl'].'mgr/widgets/home.panel.js');
        $this->addLastJavascript($this->statusoverrideips->config['jsUrl'].'mgr/sections/index.js');
    }
    public function getTemplateFile() {
        return $this->statusoverrideips->config['templatesPath'].'home.tpl';
    }
}