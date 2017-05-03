<?php
require_once(MODX_CORE_PATH.'model/modx/processors/security/user/getlist.class.php');
/**
 * Get list Items
 *
 * @package usermanagement
 * @subpackage processors
 */
class userManagementGetListProcessor extends modUserGetListProcessor {
    public $permission = '';


    public function prepareQueryBeforeCount(xPDOQuery $c) {
        $c->leftJoin('modUserProfile','Profile');

        $query = $this->getProperty('query','');
        if (!empty($query)) {
            $c->where(array(
                'modUser.username:LIKE' => '%'.$query.'%',
                'OR:Profile.fullname:LIKE' => '%'.$query.'%',
                'OR:Profile.email:LIKE' => '%'.$query.'%'
            ));
        }

        $userGroup = $this->getProperty('usergroup',0);
        $c->leftJoin('modUserGroupMember','UserGroupMembers');
        if (!empty($userGroup)) {
            $c->where(array(
                'UserGroupMembers.user_group' => $userGroup,
            ));
        }
        //Hide administrators
        $c->where(array(
            'UserGroupMembers.user_group:!=' => '1',
            'OR:UserGroupMembers.user_group:IS' => null
        ));
        return $c;
    }
}
return 'userManagementGetListProcessor';