<?php
require_once dirname(__FILE__) . '/model/modxcontrolclient/modxcontrolclient.class.php';
/**
 * @package modxcontrolclient
 */

abstract class ModxControlClientBaseManagerController extends modExtraManagerController {
    /** @var ModxControlClient $modxcontrolclient */
    public $modxcontrolclient;
    public function initialize() {
        $this->modxcontrolclient = new ModxControlClient($this->modx);

        $this->addCss($this->modxcontrolclient->getOption('cssUrl').'mgr.css');
        $this->addJavascript($this->modxcontrolclient->getOption('jsUrl').'mgr/modxcontrolclient.js');
        $this->addHtml('<script type="text/javascript">
        Ext.onReady(function() {
            ModxControlClient.config = '.$this->modx->toJSON($this->modxcontrolclient->options).';
            ModxControlClient.config.connector_url = "'.$this->modxcontrolclient->getOption('connectorUrl').'";
        });
        </script>');
        
        parent::initialize();
    }
    public function getLanguageTopics() {
        return array('modxcontrolclient:default');
    }
    public function checkPermissions() { return true;}
}