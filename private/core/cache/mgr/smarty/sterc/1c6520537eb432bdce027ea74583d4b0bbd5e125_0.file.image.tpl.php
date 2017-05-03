<?php /* Smarty version 3.1.27, created on 2017-04-14 09:06:40
         compiled from "/var/www/vhosts/narrowcasting/webroot/manager/templates/sterc/element/tv/renders/input/image.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:37646519158f07500cc6ca8_15343021%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1c6520537eb432bdce027ea74583d4b0bbd5e125' => 
    array (
      0 => '/var/www/vhosts/narrowcasting/webroot/manager/templates/sterc/element/tv/renders/input/image.tpl',
      1 => 1492151506,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '37646519158f07500cc6ca8_15343021',
  'variables' => 
  array (
    'tv' => 0,
    'params' => 0,
    '_config' => 0,
    'source' => 0,
    'disabled' => 0,
    'ctx' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.27',
  'unifunc' => 'content_58f07500eb6149_28307270',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_58f07500eb6149_28307270')) {
function content_58f07500eb6149_28307270 ($_smarty_tpl) {
if (!is_callable('smarty_modifier_replace')) require_once '/var/www/vhosts/narrowcasting/private/core/model/smarty/plugins/modifier.replace.php';

$_smarty_tpl->properties['nocache_hash'] = '37646519158f07500cc6ca8_15343021';
?>
<div id="tvbrowser<?php echo $_smarty_tpl->tpl_vars['tv']->value->id;?>
"></div>
<div id="tv-image-<?php echo $_smarty_tpl->tpl_vars['tv']->value->id;?>
"></div>
<div id="tv-image-preview-<?php echo $_smarty_tpl->tpl_vars['tv']->value->id;?>
" class="modx-tv-image-preview">

    <?php if ($_smarty_tpl->tpl_vars['tv']->value->value) {?>
        <?php if (substr($_smarty_tpl->tpl_vars['tv']->value->value,-3) == "svg") {?>
        <img src="/<?php echo $_smarty_tpl->tpl_vars['params']->value['basePath'];
echo $_smarty_tpl->tpl_vars['tv']->value->value;?>
" alt="" width="150"/>
        <?php } else { ?>
        <img src="<?php echo $_smarty_tpl->tpl_vars['_config']->value['connectors_url'];?>
system/phpthumb.php?w=400&src=<?php echo $_smarty_tpl->tpl_vars['tv']->value->value;?>
&source=<?php echo $_smarty_tpl->tpl_vars['source']->value;?>
" alt="" />
        <?php }?>
    <?php }?>
</div>
<?php if ($_smarty_tpl->tpl_vars['disabled']->value) {?>
<?php echo '<script'; ?>
 type="text/javascript">
// <![CDATA[

Ext.onReady(function() {
    var fld<?php echo $_smarty_tpl->tpl_vars['tv']->value->id;?>
 = MODx.load({
    
        xtype: 'displayfield'
        ,tv: '<?php echo $_smarty_tpl->tpl_vars['tv']->value->id;?>
'
        ,renderTo: 'tv-image-<?php echo $_smarty_tpl->tpl_vars['tv']->value->id;?>
'
        ,value: '<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['tv']->value->value, ENT_QUOTES, 'UTF-8', true);?>
'
        ,width: 400
        ,msgTarget: 'under'
    
    });
});

// ]]>
<?php echo '</script'; ?>
>
<?php } else { ?>
<?php echo '<script'; ?>
 type="text/javascript">
// <![CDATA[

Ext.onReady(function() {
    var fld<?php echo $_smarty_tpl->tpl_vars['tv']->value->id;?>
 = MODx.load({
    
        xtype: 'modx-panel-tv-image'
        ,renderTo: 'tv-image-<?php echo $_smarty_tpl->tpl_vars['tv']->value->id;?>
'
        ,tv: '<?php echo $_smarty_tpl->tpl_vars['tv']->value->id;?>
'
        ,value: '<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['tv']->value->value, ENT_QUOTES, 'UTF-8', true);?>
'
        ,relativeValue: '<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['tv']->value->value, ENT_QUOTES, 'UTF-8', true);?>
'
        ,width: 400
        ,allowBlank: <?php if ($_smarty_tpl->tpl_vars['params']->value['allowBlank'] == 1 || $_smarty_tpl->tpl_vars['params']->value['allowBlank'] == 'true') {?>true<?php } else { ?>false<?php }?>
        ,wctx: '<?php if ($_smarty_tpl->tpl_vars['params']->value['wctx']) {
echo $_smarty_tpl->tpl_vars['params']->value['wctx'];
} else { ?>web<?php }?>'
        <?php if ($_smarty_tpl->tpl_vars['params']->value['openTo']) {?>,openTo: '<?php echo smarty_modifier_replace($_smarty_tpl->tpl_vars['params']->value['openTo'],"'","\\'");?>
'<?php }?>
        ,source: '<?php echo $_smarty_tpl->tpl_vars['source']->value;?>
'
    
        ,msgTarget: 'under'
        ,listeners: {
            'select': {fn:function(data) {
                MODx.fireResourceFormChange();
                var d = Ext.get('tv-image-preview-<?php echo $_smarty_tpl->tpl_vars['tv']->value->id;?>
');
                if (Ext.isEmpty(data.url)) {
                    d.update('');
                } else {
                    
                    if(data.ext=='svg') {
                        d.update('<img src="/'+data.fullRelativeUrl+'" width="150" />');
                    } else {
                        d.update('<img src="<?php echo $_smarty_tpl->tpl_vars['_config']->value['connectors_url'];?>
system/phpthumb.php?w=400&src='+data.url+'&wctx=<?php echo $_smarty_tpl->tpl_vars['ctx']->value;?>
&source=<?php echo $_smarty_tpl->tpl_vars['source']->value;?>
" alt="" />');
                    }
                    
                }
            }}
        }
    });
    MODx.makeDroppable(Ext.get('tv-image-<?php echo $_smarty_tpl->tpl_vars['tv']->value->id;?>
'),function(v) {
        var cb = Ext.getCmp('tvbrowser<?php echo $_smarty_tpl->tpl_vars['tv']->value->id;?>
');
        if (cb) {
            cb.setValue(v);
            cb.fireEvent('select',{relativeUrl:v});
        }
        return '';
    });
});

// ]]>
<?php echo '</script'; ?>
>
<?php }

}
}
?>