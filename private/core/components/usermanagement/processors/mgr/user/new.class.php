<?php
require_once(MODX_CORE_PATH.'model/modx/processors/security/user/create.class.php');
/**
 * Update a user.
 *
 * @param integer $id The ID of the user
 *
 * @package modx
 * @subpackage processors.security.user
 */
class userManagementCreateProcessor extends modUserCreateProcessor {
    public $permission = '';


 public function cleanup() {
        $passwordNotifyMethod = $this->getProperty('passwordnotifymethod');
        if (!empty($passwordNotifyMethod) && $passwordNotifyMethod  == 's') {
            return $this->success($this->modx->lexicon('user_created_password_message',array(
                'password' => $this->newPassword,
            )),$this->object);
        } else {
            return $this->success('',$this->object);
        }
    }
}
return 'userManagementCreateProcessor';
