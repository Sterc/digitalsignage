<?php
$usermanagement = $modx->getService('usermanagement','userManagement',$modx->getOption('usermanagement.core_path',null,$modx->getOption('core_path').'components/usermanagement/').'model/usermanagement/',$scriptProperties);
if (!($usermanagement instanceof userManagement)) return '';


$m = $modx->getManager();
$m->createObjectContainer('userManagementItem');
return 'Table created.';