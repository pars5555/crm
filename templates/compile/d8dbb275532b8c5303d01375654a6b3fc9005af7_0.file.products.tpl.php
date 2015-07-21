<?php /* Smarty version 3.1.23, created on 2015-07-17 11:09:41
         compiled from "D:/xampp/htdocs/crm/templates/main/products.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:2088055a8c6555057d5_38947235%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd8dbb275532b8c5303d01375654a6b3fc9005af7' => 
    array (
      0 => 'D:/xampp/htdocs/crm/templates/main/products.tpl',
      1 => 1436252844,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2088055a8c6555057d5_38947235',
  'has_nocache_code' => false,
  'version' => '3.1.23',
  'unifunc' => 'content_55a8c65552eb16_17686302',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_55a8c65552eb16_17686302')) {
function content_55a8c65552eb16_17686302 ($_smarty_tpl) {
?>
<?php
$_smarty_tpl->properties['nocache_hash'] = '2088055a8c6555057d5_38947235';
?>
<div>
   <?php ob_start();?><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['getTemplateDir'][0][0]->getSmartyTemplateDir(array(),$_smarty_tpl);?>
<?php $_tmp1=ob_get_clean();?><?php echo $_smarty_tpl->getSubTemplate ($_tmp1."/main/left_menu.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0);
?>

   products
</div>
<?php }
}
?>