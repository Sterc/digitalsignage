<?php
require_once dirname(__FILE__) . '/model/usermanagement/usermanagement.class.php';
/**
 * @package usermanagement
 */
class IndexManagerController extends userManagementBaseManagerController {
    public static function getDefaultController() { return 'home'; }
}

abstract class userManagementBaseManagerController extends modExtraManagerController {
    /** @var userManagement $usermanagement */
    public $usermanagement;
    public function initialize() {
        $this->usermanagement = new userManagement($this->modx);

        $this->addCss($this->usermanagement->config['cssUrl'].'mgr.css');
        $this->addJavascript($this->usermanagement->config['jsUrl'].'mgr/usermanagement.js');
        $this->addHtml('<script type="text/javascript">
        Ext.onReady(function() {
            userManagement.config = '.$this->modx->toJSON($this->usermanagement->config).';
            userManagement.config.connector_url = "'.$this->usermanagement->config['connectorUrl'].'";
        });
        </script>');
        return parent::initialize();
    }
    public function getLanguageTopics() {
        return array('usermanagement:default', 'core:user');
    }
    public function checkPermissions() { return true;}
}