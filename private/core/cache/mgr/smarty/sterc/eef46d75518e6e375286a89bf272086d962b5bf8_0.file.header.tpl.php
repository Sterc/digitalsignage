<?php /* Smarty version 3.1.27, created on 2017-04-14 08:49:20
         compiled from "/var/www/vhosts/narrowcasting/webroot/manager/templates/sterc/header.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:130297185558f070f0b00c78_57528612%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'eef46d75518e6e375286a89bf272086d962b5bf8' => 
    array (
      0 => '/var/www/vhosts/narrowcasting/webroot/manager/templates/sterc/header.tpl',
      1 => 1492151506,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '130297185558f070f0b00c78_57528612',
  'variables' => 
  array (
    '_config' => 0,
    '_pagetitle' => 0,
    'indexCss' => 0,
    '_lang_topics' => 0,
    '_ctx' => 0,
    'maincssjs' => 0,
    'cssjs' => 0,
    'scr' => 0,
    '_search' => 0,
    'modx' => 0,
    'profile' => 0,
    'username' => 0,
    'userNav' => 0,
    '_lang' => 0,
    'navb' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.27',
  'unifunc' => 'content_58f070f0d980f8_68598698',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_58f070f0d980f8_68598698')) {
function content_58f070f0d980f8_68598698 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '130297185558f070f0b00c78_57528612';
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo $_smarty_tpl->tpl_vars['_config']->value['manager_direction'];?>
" lang="<?php echo $_smarty_tpl->tpl_vars['_config']->value['manager_lang_attribute'];?>
" xml:lang="<?php echo $_smarty_tpl->tpl_vars['_config']->value['manager_lang_attribute'];?>
"<?php if ($_smarty_tpl->tpl_vars['_config']->value['manager_html5_cache'] == 1) {?> manifest="<?php echo $_smarty_tpl->tpl_vars['_config']->value['manager_url'];?>
cache.manifest.php"<?php }?>>
<head>
<title><?php if ($_smarty_tpl->tpl_vars['_pagetitle']->value) {
echo $_smarty_tpl->tpl_vars['_pagetitle']->value;?>
 | <?php }
echo htmlspecialchars(preg_replace('!<[^>]*?>!', ' ', $_smarty_tpl->tpl_vars['_config']->value['site_name']), ENT_QUOTES, 'UTF-8', true);?>
</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $_smarty_tpl->tpl_vars['_config']->value['modx_charset'];?>
" />

<?php if ($_smarty_tpl->tpl_vars['_config']->value['manager_favicon_url']) {?><link rel="shortcut icon" href="<?php echo $_smarty_tpl->tpl_vars['_config']->value['manager_favicon_url'];?>
" /><?php }?>

<link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['_config']->value['manager_url'];?>
assets/ext3/resources/css/ext-all-notheme-min.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['indexCss']->value;?>
" />
<link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['_config']->value['manager_url'];?>
templates/sterc/css/sterc.css" />

<?php if ($_smarty_tpl->tpl_vars['_config']->value['ext_debug']) {?>
<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['_config']->value['manager_url'];?>
assets/ext3/adapter/ext/ext-base-debug.js" type="text/javascript"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['_config']->value['manager_url'];?>
assets/ext3/ext-all-debug.js" type="text/javascript"><?php echo '</script'; ?>
>
<?php } else { ?>
<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['_config']->value['manager_url'];?>
assets/ext3/adapter/ext/ext-base.js" type="text/javascript"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['_config']->value['manager_url'];?>
assets/ext3/ext-all.js" type="text/javascript"><?php echo '</script'; ?>
>
<?php }?>
<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['_config']->value['manager_url'];?>
assets/modext/core/modx.js" type="text/javascript"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['_config']->value['connectors_url'];?>
lang.js.php?ctx=mgr&topic=topmenu,file,resource,<?php echo $_smarty_tpl->tpl_vars['_lang_topics']->value;?>
&action=<?php echo htmlspecialchars($_GET['a']);?>
" type="text/javascript"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['_config']->value['connectors_url'];?>
modx.config.js.php?action=<?php echo htmlspecialchars($_GET['a']);
if ($_smarty_tpl->tpl_vars['_ctx']->value) {?>&wctx=<?php echo $_smarty_tpl->tpl_vars['_ctx']->value;
}?>" type="text/javascript"><?php echo '</script'; ?>
>

<?php if ($_smarty_tpl->tpl_vars['_config']->value['compress_js'] && $_smarty_tpl->tpl_vars['_config']->value['compress_js_groups']) {?>
<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['_config']->value['manager_url'];?>
min/index.php?g=coreJs1" type="text/javascript"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['_config']->value['manager_url'];?>
min/index.php?g=coreJs2" type="text/javascript"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['_config']->value['manager_url'];?>
min/index.php?g=coreJs3" type="text/javascript"><?php echo '</script'; ?>
>
<?php }?>

<?php echo $_smarty_tpl->tpl_vars['maincssjs']->value;?>

<?php
$_from = $_smarty_tpl->tpl_vars['cssjs']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['scr'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['scr']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['scr']->value) {
$_smarty_tpl->tpl_vars['scr']->_loop = true;
$foreach_scr_Sav = $_smarty_tpl->tpl_vars['scr'];
?>
<?php echo $_smarty_tpl->tpl_vars['scr']->value;?>

<?php
$_smarty_tpl->tpl_vars['scr'] = $foreach_scr_Sav;
}
?>

<?php echo '<script'; ?>
 type="text/javascript">
    Ext.onReady(function() {
        // Enable site name tooltip (on overflow only)
        if( Ext.get('site_name').dom.scrollWidth > Ext.get('site_name').dom.clientWidth ){
          new Ext.ToolTip({
              title: Ext.get('site_name').dom.title
              ,target: Ext.get('site_name')
          });
        }
        <?php if ($_smarty_tpl->tpl_vars['_search']->value) {?>
        new MODx.SearchBar;
        <?php }?>
    });
<?php echo '</script'; ?>
>

</head>
<body id="modx-body-tag">

<div id="modx-browser"></div>
<div id="modx-container">
    <div id="modx-header">
        <div id="modx-navbar">
            <ul id="modx-user-menu">
                
                <?php if (isset($_smarty_tpl->tpl_vars['profile'])) {$_smarty_tpl->tpl_vars['profile'] = clone $_smarty_tpl->tpl_vars['profile'];
$_smarty_tpl->tpl_vars['profile']->value = $_smarty_tpl->tpl_vars['modx']->value->user->getOne('Profile'); $_smarty_tpl->tpl_vars['profile']->nocache = null; $_smarty_tpl->tpl_vars['profile']->scope = 0;
} else $_smarty_tpl->tpl_vars['profile'] = new Smarty_Variable($_smarty_tpl->tpl_vars['modx']->value->user->getOne('Profile'), null, 0);?>
                <?php if (isset($_smarty_tpl->tpl_vars['username'])) {$_smarty_tpl->tpl_vars['username'] = clone $_smarty_tpl->tpl_vars['username'];
$_smarty_tpl->tpl_vars['username']->value = $_smarty_tpl->tpl_vars['profile']->value->get('fullname') ? $_smarty_tpl->tpl_vars['profile']->value->get('fullname') : $_smarty_tpl->tpl_vars['username']->value; $_smarty_tpl->tpl_vars['username']->nocache = null; $_smarty_tpl->tpl_vars['username']->scope = 0;
} else $_smarty_tpl->tpl_vars['username'] = new Smarty_Variable($_smarty_tpl->tpl_vars['profile']->value->get('fullname') ? $_smarty_tpl->tpl_vars['profile']->value->get('fullname') : $_smarty_tpl->tpl_vars['username']->value, null, 0);?>
                <?php $_template = new Smarty_Internal_Template('eval:'.$_smarty_tpl->tpl_vars['userNav']->value, $_smarty_tpl->smarty, $_smarty_tpl);echo $_template->fetch(); ?>
                <li id="sterc-logo"><img src="<?php echo $_smarty_tpl->tpl_vars['_config']->value['manager_url'];?>
templates/sterc/images/sterc-logo-small.png"/></li>
            </ul>

            <ul id="modx-topnav">
                <li id="modx-home-dashboard">
                    <a href="?" title="<?php echo $_smarty_tpl->tpl_vars['_lang']->value['dashboard'];?>
"><?php echo $_smarty_tpl->tpl_vars['_lang']->value['dashboard'];?>
</a>
                </li>
                <li id="modx-site-info">
                    <div id="site_name" class="info-item site_name" title="<?php echo htmlspecialchars(preg_replace('!<[^>]*?>!', ' ', $_smarty_tpl->tpl_vars['_config']->value['site_name']), ENT_QUOTES, 'UTF-8', true);?>
"><?php echo htmlspecialchars(preg_replace('!<[^>]*?>!', ' ', $_smarty_tpl->tpl_vars['_config']->value['site_name']), ENT_QUOTES, 'UTF-8', true);?>
</div>
                    
                    <div class="info-item full_appname">MODX Revolution <?php echo $_smarty_tpl->tpl_vars['_config']->value['settings_version'];?>
</div>
                </li>
                <?php if ($_smarty_tpl->tpl_vars['_search']->value) {?>
                <li id="modx-manager-search-icon">
                    <a href="javascript:;" onclick="Ext.getCmp('modx-uberbar').toggle()" title="<?php echo $_smarty_tpl->tpl_vars['_lang']->value['search'];?>
">
                        <span class="icon-stack icon-lg">
                            <i class="icon icon-square icon-stack-2x"></i>
                            <i class="icon icon-search icon-stack-1x"></i>
                        </span>
                    </a>
                </li>
                <?php }?>
                <?php echo $_smarty_tpl->tpl_vars['navb']->value;?>

            </ul>
            <?php if ($_smarty_tpl->tpl_vars['_search']->value) {?>
                <div id="modx-manager-search"></div>
            <?php }?>
        </div>
    </div>
        <div id="modAB"></div>
        <div id="modx-leftbar"></div>
        <div id="modx-content">
            <div id="modx-panel-holder"></div>
<?php }
}
?>