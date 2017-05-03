<?php
require_once dirname(dirname(__FILE__)) . '/index.class.php';
/**
 * Loads the home page.
 *
 * @package modxcontrolclient
 * @subpackage controllers
 */
class ModxControlClientHomeManagerController extends ModxControlClientBaseManagerController {
    public function process(array $scriptProperties = array()) {

    }
    public function getPageTitle() { return $this->modx->lexicon('modxcontrolclient'); }
    public function loadCustomCssJs() {
    
    }

}