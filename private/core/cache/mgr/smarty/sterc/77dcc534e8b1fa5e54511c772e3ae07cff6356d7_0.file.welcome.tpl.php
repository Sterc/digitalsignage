<?php /* Smarty version 3.1.27, created on 2017-04-14 08:49:20
         compiled from "/var/www/vhosts/narrowcasting/webroot/manager/templates/default/welcome.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:144846008358f070f0e736e4_79614512%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '77dcc534e8b1fa5e54511c772e3ae07cff6356d7' => 
    array (
      0 => '/var/www/vhosts/narrowcasting/webroot/manager/templates/default/welcome.tpl',
      1 => 1492151506,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '144846008358f070f0e736e4_79614512',
  'variables' => 
  array (
    'dashboard' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.27',
  'unifunc' => 'content_58f070f0f3c2c8_05733832',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_58f070f0f3c2c8_05733832')) {
function content_58f070f0f3c2c8_05733832 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '144846008358f070f0e736e4_79614512';
?>
<div id="modx-panel-welcome-div"></div>

<div id="modx-dashboard" class="dashboard">
<?php echo $_smarty_tpl->tpl_vars['dashboard']->value;?>

</div><?php }
}
?>