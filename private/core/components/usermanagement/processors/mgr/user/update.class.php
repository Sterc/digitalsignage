<?php
require_once(MODX_CORE_PATH.'model/modx/processors/security/user/update.class.php');
/**
 * Update a user.
 *
 * @param integer $id The ID of the user
 *
 * @package modx
 * @subpackage processors.security.user
 */
class userManagementUpdateProcessor extends modUserUpdateProcessor {
    public $permission = '';

    public function beforeSet() {
		if($this->getProperty('blocked') == 0){
			$this->setProperty('blockeduntil', 0);
		}
        return parent::beforeSet();	
	}
}
return 'userManagementUpdateProcessor';
