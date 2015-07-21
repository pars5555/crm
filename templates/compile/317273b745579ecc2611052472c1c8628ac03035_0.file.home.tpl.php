<?php /* Smarty version 3.1.23, created on 2015-07-07 08:59:41
         compiled from "D:/xampp/htdocs/crm/templates/main/home.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:301559b78ddc37f00_93999800%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '317273b745579ecc2611052472c1c8628ac03035' => 
    array (
      0 => 'D:/xampp/htdocs/crm/templates/main/home.tpl',
      1 => 1436252380,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '301559b78ddc37f00_93999800',
  'has_nocache_code' => false,
  'version' => '3.1.23',
  'unifunc' => 'content_559b78ddc62805_18256289',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_559b78ddc62805_18256289')) {
function content_559b78ddc62805_18256289 ($_smarty_tpl) {
?>
<?php
$_smarty_tpl->properties['nocache_hash'] = '301559b78ddc37f00_93999800';
?>
<div>
   <?php ob_start();?><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['getTemplateDir'][0][0]->getSmartyTemplateDir(array(),$_smarty_tpl);?>
<?php $_tmp1=ob_get_clean();?><?php echo $_smarty_tpl->getSubTemplate ($_tmp1."/main/left_menu.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0);
?>

</div>
<?php }
}
?>