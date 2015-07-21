<?php /* Smarty version 3.1.23, created on 2015-07-21 11:08:47
         compiled from "D:/xampp/htdocs/crm/templates/main/payment/payments_list_filters.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:1403455ae0c1fa836a9_56247074%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'dbb9aacfa1f355ee56bf65e9370ea955897b160d' => 
    array (
      0 => 'D:/xampp/htdocs/crm/templates/main/payment/payments_list_filters.tpl',
      1 => 1437469722,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1403455ae0c1fa836a9_56247074',
  'variables' => 
  array (
    'ns' => 0,
    'p' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.23',
  'unifunc' => 'content_55ae0c1fac7c23_39814343',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_55ae0c1fac7c23_39814343')) {
function content_55ae0c1fac7c23_39814343 ($_smarty_tpl) {
?>
<?php
$_smarty_tpl->properties['nocache_hash'] = '1403455ae0c1fa836a9_56247074';
?>
<div>
    <h2>Filters</h2>
    <label>Partner</label>
    <select name="filterPartnerId">
        <?php
$_from = $_smarty_tpl->tpl_vars['ns']->value['partners'];
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['p'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['p']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['p']->value) {
$_smarty_tpl->tpl_vars['p']->_loop = true;
$foreachItemSav = $_smarty_tpl->tpl_vars['p'];
?>
            <option value="<?php echo $_smarty_tpl->tpl_vars['p']->value->getId();?>
" <?php if (isset($_smarty_tpl->tpl_vars['ns']->value['selectedFilterPartnerId'])&&$_smarty_tpl->tpl_vars['ns']->value['selectedFilterPartnerId']==$_smarty_tpl->tpl_vars['p']->value->getId()) {?>selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['p']->value->getName();?>
</option>
        <?php
$_smarty_tpl->tpl_vars['p'] = $foreachItemSav;
}
?>
    </select>
</div>
<?php }
}
?>