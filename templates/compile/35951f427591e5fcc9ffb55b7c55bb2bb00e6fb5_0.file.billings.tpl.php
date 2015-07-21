<?php /* Smarty version 3.1.23, created on 2015-07-17 11:16:28
         compiled from "D:/xampp/htdocs/crm/templates/main/billings.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:160555a8c7ec763443_98372297%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '35951f427591e5fcc9ffb55b7c55bb2bb00e6fb5' => 
    array (
      0 => 'D:/xampp/htdocs/crm/templates/main/billings.tpl',
      1 => 1437124587,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '160555a8c7ec763443_98372297',
  'has_nocache_code' => false,
  'version' => '3.1.23',
  'unifunc' => 'content_55a8c7ec78fde1_38560450',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_55a8c7ec78fde1_38560450')) {
function content_55a8c7ec78fde1_38560450 ($_smarty_tpl) {
?>
<?php
$_smarty_tpl->properties['nocache_hash'] = '160555a8c7ec763443_98372297';
?>
<div>
   <?php ob_start();?><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['getTemplateDir'][0][0]->getSmartyTemplateDir(array(),$_smarty_tpl);?>
<?php $_tmp1=ob_get_clean();?><?php echo $_smarty_tpl->getSubTemplate ($_tmp1."/main/left_menu.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0);
?>

   billings
</div>
<?php }
}
?>