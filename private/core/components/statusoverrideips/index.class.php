<?php
require_once dirname(__FILE__) . '/model/statusoverrideips/statusoverrideips.class.php';
abstract class StatusOverrideIPsManagerController extends modExtraManagerController {
    /** @var StatusOverrideIPs $statusoverrideips */
    public $statusoverrideips;
    public function initialize() {
        $this->statusoverrideips = new StatusOverrideIPs($this->modx);
 
        $this->addJavascript($this->statusoverrideips->config['jsUrl'].'mgr/statusoverrideips.js');
        $this->addHtml('<script type="text/javascript">
        Ext.onReady(function() {
            StatusOverrideIPs.config = '.$this->modx->toJSON($this->statusoverrideips->config).';
        });
        </script>');
        return parent::initialize();
    }
    public function getLanguageTopics() {
        return array('statusoverrideips:default');
    }
    public function checkPermissions() { return true;}
}
class IndexManagerController extends StatusOverrideIPsManagerController {
    public static function getDefaultController() { return 'home'; }
}