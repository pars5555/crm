<?php /* Smarty version 3.1.23, created on 2015-07-17 11:16:12
         compiled from "D:/xampp/htdocs/crm/templates/main/left_menu.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:1089455a8c7dc3a1935_27227661%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '65bfe9d605f261436e70260e067cafe21a4d6d9b' => 
    array (
      0 => 'D:/xampp/htdocs/crm/templates/main/left_menu.tpl',
      1 => 1437124571,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1089455a8c7dc3a1935_27227661',
  'has_nocache_code' => false,
  'version' => '3.1.23',
  'unifunc' => 'content_55a8c7dc3ccda8_68774431',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_55a8c7dc3ccda8_68774431')) {
function content_55a8c7dc3ccda8_68774431 ($_smarty_tpl) {
?>
<?php
$_smarty_tpl->properties['nocache_hash'] = '1089455a8c7dc3a1935_27227661';
?>
<div>
    <ul>
        <li><a href="<?php echo SITE_PATH;?>
/general">General</a></li>
        <li><a href="<?php echo SITE_PATH;?>
/sales">Sale Orders</a></li>
        <li><a href="<?php echo SITE_PATH;?>
/purchases">Purchase Orders</a></li>
        <li><a href="<?php echo SITE_PATH;?>
/payments">Payment Order</a></li>
        <li><a href="<?php echo SITE_PATH;?>
/billings">Billing Order</a></li>
        <li><a href="<?php echo SITE_PATH;?>
/warehouse">Warehouse</a></li>
        <li><a href="<?php echo SITE_PATH;?>
/products">Products</a></li>
    </ul>
</div>
<?php }
}
?>