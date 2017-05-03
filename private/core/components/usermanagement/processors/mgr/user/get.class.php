<?php
require_once(MODX_CORE_PATH.'model/modx/processors/security/user/get.class.php');
/**
 * Get list Items
 *
 * @package usermanagement
 * @subpackage processors
 */
class userManagementGetProcessor extends modUserGetProcessor {
    public $permission = '';
}
return 'userManagementGetProcessor';