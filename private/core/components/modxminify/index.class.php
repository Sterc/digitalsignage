<?php
require_once dirname(__FILE__) . '/model/modxminify/modxminify.class.php';
/**
 * @package modxminify
 */

abstract class modxMinifyBaseManagerController extends modExtraManagerController {
    /** @var modxMinify $modxminify */
    public $modxminify;
    public function initialize() {
        $this->modxminify = new modxMinify($this->modx);

        $this->addCss($this->modxminify->getOption('cssUrl').'mgr.css');
        $this->addJavascript($this->modxminify->getOption('jsUrl').'mgr/modxminify.js');
        $this->addHtml('<script type="text/javascript">
        Ext.onReady(function() {
            modxMinify.config = '.$this->modx->toJSON($this->modxminify->options).';
            modxMinify.config.connector_url = "'.$this->modxminify->getOption('connectorUrl').'";
        });
        </script>');
        
        parent::initialize();
    }
    public function getLanguageTopics() {
        return array('modxminify:default');
    }
    public function checkPermissions() { return true;}
}