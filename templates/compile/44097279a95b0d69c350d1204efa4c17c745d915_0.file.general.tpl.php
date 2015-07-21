<?php /* Smarty version 3.1.23, created on 2015-07-07 09:08:59
         compiled from "D:/xampp/htdocs/crm/templates/main/general.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:24506559b7b0bca10a6_91246053%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '44097279a95b0d69c350d1204efa4c17c745d915' => 
    array (
      0 => 'D:/xampp/htdocs/crm/templates/main/general.tpl',
      1 => 1436252938,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '24506559b7b0bca10a6_91246053',
  'has_nocache_code' => false,
  'version' => '3.1.23',
  'unifunc' => 'content_559b7b0bce6149_63407378',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_559b7b0bce6149_63407378')) {
function content_559b7b0bce6149_63407378 ($_smarty_tpl) {
?>
<?php
$_smarty_tpl->properties['nocache_hash'] = '24506559b7b0bca10a6_91246053';
?>
<div>
   <?php ob_start();?><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['getTemplateDir'][0][0]->getSmartyTemplateDir(array(),$_smarty_tpl);?>
<?php $_tmp1=ob_get_clean();?><?php echo $_smarty_tpl->getSubTemplate ($_tmp1."/main/left_menu.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0);
?>

   general
</div>
<?php }
}
?>