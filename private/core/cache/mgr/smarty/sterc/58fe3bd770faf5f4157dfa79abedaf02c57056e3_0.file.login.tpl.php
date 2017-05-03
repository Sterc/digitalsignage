<?php /* Smarty version 3.1.27, created on 2017-04-14 08:47:52
         compiled from "/var/www/vhosts/narrowcasting/webroot/manager/templates/sterc/security/login.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:137806544558f070982be8b2_34989688%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '58fe3bd770faf5f4157dfa79abedaf02c57056e3' => 
    array (
      0 => '/var/www/vhosts/narrowcasting/webroot/manager/templates/sterc/security/login.tpl',
      1 => 1492151507,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '137806544558f070982be8b2_34989688',
  'variables' => 
  array (
    '_config' => 0,
    '_lang' => 0,
    'language_str' => 0,
    'languages' => 0,
    'onManagerLoginFormPrerender' => 0,
    'modahsh' => 0,
    'returnUrl' => 0,
    'error_message' => 0,
    '_post' => 0,
    'onManagerLoginFormRender' => 0,
    'allow_forgot_password' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.27',
  'unifunc' => 'content_58f070983f66d3_87656248',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_58f070983f66d3_87656248')) {
function content_58f070983f66d3_87656248 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '137806544558f070982be8b2_34989688';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php if ($_smarty_tpl->tpl_vars['_config']->value['manager_direction'] == 'rtl') {?>dir="rtl"<?php }?> lang="<?php echo $_smarty_tpl->tpl_vars['_config']->value['manager_lang_attribute'];?>
" xml:lang="<?php echo $_smarty_tpl->tpl_vars['_config']->value['manager_lang_attribute'];?>
">
<head>
	<title><?php echo $_smarty_tpl->tpl_vars['_lang']->value['login_title'];?>
</title>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $_smarty_tpl->tpl_vars['_config']->value['modx_charset'];?>
" />
    <?php if ($_smarty_tpl->tpl_vars['_config']->value['manager_favicon_url']) {?><link rel="shortcut icon" type="image/x-icon" href="<?php echo $_smarty_tpl->tpl_vars['_config']->value['manager_favicon_url'];?>
" /><?php }?>

    <link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['_config']->value['manager_url'];?>
assets/ext3/resources/css/ext-all-notheme-min.css" />
  	<link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['_config']->value['manager_url'];?>
templates/default/css/index.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['_config']->value['manager_url'];?>
templates/default/css/login.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['_config']->value['manager_url'];?>
templates/sterc/css/client.css" />

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
 src="assets/modext/core/modx.js" type="text/javascript"><?php echo '</script'; ?>
>

    <?php echo '<script'; ?>
 src="assets/modext/core/modx.component.js" type="text/javascript"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="assets/modext/util/utilities.js" type="text/javascript"><?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
 src="assets/modext/widgets/core/modx.panel.js" type="text/javascript"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="assets/modext/widgets/core/modx.window.js" type="text/javascript"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="assets/modext/sections/login.js" type="text/javascript"><?php echo '</script'; ?>
>

    <meta name="robots" content="noindex, nofollow" />
</head>

<body id="login">

<!--[if IE]>
  <div class="outdated-browser-warning">
    <div class="message">
      De MODX-manager maakt gebruik van functies die niet altijd goed werken in Internet Explorer.  We raden je aan om gebruik te maken van een andere browser.
      <div class="clear"></div>
    </div>
    <div class="clear"></div>
  </div>
<![endif]-->
<div id="modx-login-language-select-div">
    <label><?php echo $_smarty_tpl->tpl_vars['language_str']->value;?>
:
    <select name="cultureKey" id="modx-login-language-select">
        <?php echo $_smarty_tpl->tpl_vars['languages']->value;?>

    </select>
    </label>
</div>
<?php echo $_smarty_tpl->tpl_vars['onManagerLoginFormPrerender']->value;?>

<br />

<div id="container">
    <div id="modx-login-logo">
        <img src="<?php echo $_smarty_tpl->tpl_vars['_config']->value['manager_url'];?>
templates/sterc/images/client-logo.png" alt="" />
    </div>

<div id="modx-panel-login-div" class="x-panel modx-form x-form-label-right">

    <form id="modx-login-form" action="" method="post">
        <input type="hidden" name="login_context" value="mgr" />
        <input type="hidden" name="modahsh" value="<?php echo $_smarty_tpl->tpl_vars['modahsh']->value;?>
" />
        <input type="hidden" name="returnUrl" value="<?php echo $_smarty_tpl->tpl_vars['returnUrl']->value;?>
" />

        <div class="x-panel x-panel-noborder"><div class="x-panel-bwrap"><div class="x-panel-body x-panel-body-noheader">
        <h2><?php echo $_smarty_tpl->tpl_vars['_config']->value['site_name'];?>
</h2>
        <br class="clear" />

        <?php if ($_smarty_tpl->tpl_vars['error_message']->value) {?><p class="error"><?php echo $_smarty_tpl->tpl_vars['error_message']->value;?>
</p><?php }?>
        </div></div></div>

        <div class="x-form-item login-form-item login-form-item-first">
          <label for="modx-login-username">Username</label>
          <div class="x-form-element login-form-element">
            <input type="text" id="modx-login-username" name="username" tabindex="1" autocomplete="on" value="<?php echo $_smarty_tpl->tpl_vars['_post']->value['username'];?>
" class="x-form-text x-form-field" placeholder="<?php echo $_smarty_tpl->tpl_vars['_lang']->value['login_username'];?>
" />
          </div>
        </div>

        <div class="x-form-item login-form-item">
          <label for="modx-login-password">Password</label>
          <div class="x-form-element login-form-element">
            <input type="password" id="modx-login-password" name="password" tabindex="2" autocomplete="on" class="x-form-text x-form-field" placeholder="<?php echo $_smarty_tpl->tpl_vars['_lang']->value['login_password'];?>
" />
          </div>
        </div>


        <div class="login-cb-row">
            <div class="login-cb-col one">
                <div class="modx-login-fl-link">
                   <a href="javascript:void(0);" id="modx-fl-link" style="<?php if ($_smarty_tpl->tpl_vars['_post']->value['username_reset']) {?>display:none;<?php }?>"><?php echo $_smarty_tpl->tpl_vars['_lang']->value['login_forget_your_login'];?>
</a>
                </div>
            </div>
            <div class="login-cb-col two">
                <div class="x-form-check-wrap modx-login-rm-cb">
                    <input type="checkbox" id="modx-login-rememberme" name="rememberme" tabindex="3" autocomplete="on" <?php if ($_smarty_tpl->tpl_vars['_post']->value['rememberme']) {?>checked="checked"<?php }?> class="x-form-checkbox x-form-field" value="1" />
                    <label for="modx-login-rememberme" class="x-form-cb-label"><?php echo $_smarty_tpl->tpl_vars['_lang']->value['login_remember'];?>
</label>
                </div>
                <button class="x-btn x-btn-small x-btn-icon-small-left primary-button x-btn-noicon login-form-btn" name="login" type="submit" value="1" id="modx-login-btn" tabindex="4"><?php echo $_smarty_tpl->tpl_vars['_lang']->value['login_button'];?>
</button>
            </div>
        </div>

        <?php echo $_smarty_tpl->tpl_vars['onManagerLoginFormRender']->value;?>

    </form>

    <?php if ($_smarty_tpl->tpl_vars['allow_forgot_password']->value) {?>
      <div class="modx-forgot-login">
        <form id="modx-fl-form" action="" method="post">
           <div id="modx-forgot-login-form" style="<?php if (!$_smarty_tpl->tpl_vars['_post']->value['username_reset']) {?>display: none;<?php }?>">

               <div class="x-form-item login-form-item">
                  <div class="x-form-element login-form-element">
                    <input type="text" id="modx-login-username-reset" name="username_reset" class="x-form-text x-form-field" value="<?php echo $_smarty_tpl->tpl_vars['_post']->value['username_reset'];?>
" placeholder="<?php echo $_smarty_tpl->tpl_vars['_lang']->value['login_username_or_email'];?>
" />
                  </div>
                  <div class="x-form-clear-left"></div>
               </div>


               <button class="x-btn x-btn-small x-btn-icon-small-left primary-button x-btn-noicon login-form-btn" name="forgotlogin" type="submit" value="1" id="modx-fl-btn"><?php echo $_smarty_tpl->tpl_vars['_lang']->value['login_send_activation_email'];?>
</button>

           </div>
        </form>
        </div>
    <?php }?>

    <br class="clear" />
</div>

</div>

</body>
</html>
<?php }
}
?>