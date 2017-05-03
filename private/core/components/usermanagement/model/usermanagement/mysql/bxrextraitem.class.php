<?php
/**
 * @package usermanagement
 */
require_once (strtr(realpath(dirname(dirname(__FILE__))), '\\', '/') . '/usermanagementitem.class.php');
class userManagementItem_mysql extends userManagementItem {}
?>