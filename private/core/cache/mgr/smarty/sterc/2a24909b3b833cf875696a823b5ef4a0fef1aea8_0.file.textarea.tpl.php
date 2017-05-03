<?php /* Smarty version 3.1.27, created on 2017-04-14 09:05:33
         compiled from "/var/www/vhosts/narrowcasting/webroot/manager/templates/default/element/tv/renders/input/textarea.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:52782722358f074bd20da62_71199711%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '2a24909b3b833cf875696a823b5ef4a0fef1aea8' => 
    array (
      0 => '/var/www/vhosts/narrowcasting/webroot/manager/templates/default/element/tv/renders/input/textarea.tpl',
      1 => 1492151504,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '52782722358f074bd20da62_71199711',
  'variables' => 
  array (
    'tv' => 0,
    'params' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.27',
  'unifunc' => 'content_58f074bd369ad1_28668752',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_58f074bd369ad1_28668752')) {
function content_58f074bd369ad1_28668752 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '52782722358f074bd20da62_71199711';
?>
<textarea id="tv<?php echo $_smarty_tpl->tpl_vars['tv']->value->id;?>
" name="tv<?php echo $_smarty_tpl->tpl_vars['tv']->value->id;?>
" rows="15"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['tv']->value->get('value'), ENT_QUOTES, 'UTF-8', true);?>
</textarea>

<?php echo '<script'; ?>
 type="text/javascript">
// <![CDATA[

Ext.onReady(function() {
    var fld = MODx.load({
    
        xtype: 'textarea'
        ,applyTo: 'tv<?php echo $_smarty_tpl->tpl_vars['tv']->value->id;?>
'
        ,value: '<?php echo strtr($_smarty_tpl->tpl_vars['tv']->value->get('value'), array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
'
        ,height: 140
        ,width: '99%'
        ,enableKeyEvents: true
        ,msgTarget: 'under'
        ,allowBlank: <?php if ($_smarty_tpl->tpl_vars['params']->value['allowBlank'] == 1 || $_smarty_tpl->tpl_vars['params']->value['allowBlank'] == 'true') {?>true<?php } else { ?>false<?php }?>
    
        ,listeners: { 'keydown': { fn:MODx.fireResourceFormChange, scope:this}}
    });
    MODx.makeDroppable(fld);
    Ext.getCmp('modx-panel-resource').getForm().add(fld);
});

// ]]>
<?php echo '</script'; ?>
>
<?php }
}
?>