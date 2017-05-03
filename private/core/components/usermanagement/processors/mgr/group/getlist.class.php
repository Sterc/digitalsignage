<?php
require_once(MODX_CORE_PATH.'model/modx/processors/security/group/getlist.class.php');
/**
 * Get list Items
 *
 * @package usermanagement
 * @subpackage processors
 */
class userManagementGetListProcessor extends modUserGroupGetListProcessor {
    public $permission = '';
}
return 'userManagementGetListProcessor';