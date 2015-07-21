<?php /* Smarty version 3.1.23, created on 2015-07-17 11:09:40
         compiled from "D:/xampp/htdocs/crm/templates/main/warehouse.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:110755a8c654d45254_26055525%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'cfc3d121946894241003d05654cd4e072cf84977' => 
    array (
      0 => 'D:/xampp/htdocs/crm/templates/main/warehouse.tpl',
      1 => 1436252854,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '110755a8c654d45254_26055525',
  'has_nocache_code' => false,
  'version' => '3.1.23',
  'unifunc' => 'content_55a8c654d77863_36100531',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_55a8c654d77863_36100531')) {
function content_55a8c654d77863_36100531 ($_smarty_tpl) {
?>
<?php
$_smarty_tpl->properties['nocache_hash'] = '110755a8c654d45254_26055525';
?>
<div>
   <?php ob_start();?><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['getTemplateDir'][0][0]->getSmartyTemplateDir(array(),$_smarty_tpl);?>
<?php $_tmp1=ob_get_clean();?><?php echo $_smarty_tpl->getSubTemplate ($_tmp1."/main/left_menu.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0);
?>

   warehouse
</div>
<?php }
}
?>