<?php /* Smarty version 3.1.23, created on 2015-07-25 12:18:29
         compiled from "D:/xampp/htdocs/crm/templates/main/payment/payments_list_filters.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:2527355b362757477b4_16375009%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'dbb9aacfa1f355ee56bf65e9370ea955897b160d' => 
    array (
      0 => 'D:/xampp/htdocs/crm/templates/main/payment/payments_list_filters.tpl',
      1 => 1437819463,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2527355b362757477b4_16375009',
  'variables' => 
  array (
    'ns' => 0,
    'p' => 0,
    'c' => 0,
    'fieldName' => 0,
    'fieldDisplayName' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.23',
  'unifunc' => 'content_55b362757bab53_73047110',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_55b362757bab53_73047110')) {
function content_55b362757bab53_73047110 ($_smarty_tpl) {
?>
<?php
$_smarty_tpl->properties['nocache_hash'] = '2527355b362757477b4_16375009';
?>
<form id="paymentFilters" autocomplete="off" action="<?php echo SITE_PATH;?>
/payments" method="GET">
    <h2>Filters</h2>
    <label>Partner</label>
    <select name="prt">
        <option value="0" <?php if ($_smarty_tpl->tpl_vars['ns']->value['selectedFilterPartnerId']==0) {?>selected<?php }?>>All</option>
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
" <?php if ($_smarty_tpl->tpl_vars['ns']->value['selectedFilterPartnerId']==$_smarty_tpl->tpl_vars['p']->value->getId()) {?>selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['p']->value->getName();?>
</option>
        <?php
$_smarty_tpl->tpl_vars['p'] = $foreachItemSav;
}
?>
    </select>
    <label>Currency</label>
    <select name="cur">
        <option value="0" <?php if ($_smarty_tpl->tpl_vars['ns']->value['selectedFilterCurrencyId']==0) {?>selected<?php }?>>All</option>
        <?php
$_from = $_smarty_tpl->tpl_vars['ns']->value['currencies'];
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['c'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['c']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['c']->value) {
$_smarty_tpl->tpl_vars['c']->_loop = true;
$foreachItemSav = $_smarty_tpl->tpl_vars['c'];
?>
            <option value="<?php echo $_smarty_tpl->tpl_vars['c']->value->getId();?>
" <?php if ($_smarty_tpl->tpl_vars['ns']->value['selectedFilterCurrencyId']==$_smarty_tpl->tpl_vars['c']->value->getId()) {?>selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['c']->value->getTemplateChar();?>
</option>
        <?php
$_smarty_tpl->tpl_vars['c'] = $foreachItemSav;
}
?>
    </select>
    <label>Page</label>
    <select name="pg">
        <?php $_smarty_tpl->tpl_vars['p'] = new Smarty_Variable;$_smarty_tpl->tpl_vars['p']->step = 1;$_smarty_tpl->tpl_vars['p']->total = (int) ceil(($_smarty_tpl->tpl_vars['p']->step > 0 ? $_smarty_tpl->tpl_vars['ns']->value['pagesCount']+1 - (1) : 1-($_smarty_tpl->tpl_vars['ns']->value['pagesCount'])+1)/abs($_smarty_tpl->tpl_vars['p']->step));
if ($_smarty_tpl->tpl_vars['p']->total > 0) {
for ($_smarty_tpl->tpl_vars['p']->value = 1, $_smarty_tpl->tpl_vars['p']->iteration = 1;$_smarty_tpl->tpl_vars['p']->iteration <= $_smarty_tpl->tpl_vars['p']->total;$_smarty_tpl->tpl_vars['p']->value += $_smarty_tpl->tpl_vars['p']->step, $_smarty_tpl->tpl_vars['p']->iteration++) {
$_smarty_tpl->tpl_vars['p']->first = $_smarty_tpl->tpl_vars['p']->iteration == 1;$_smarty_tpl->tpl_vars['p']->last = $_smarty_tpl->tpl_vars['p']->iteration == $_smarty_tpl->tpl_vars['p']->total;?>
            <option value="<?php echo $_smarty_tpl->tpl_vars['p']->value;?>
" <?php if ($_smarty_tpl->tpl_vars['ns']->value['selectedFilterPage']==$_smarty_tpl->tpl_vars['p']->value) {?>selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['p']->value;?>
</option>
        <?php }} ?>
    </select>
    <label>Sort by </label>
    <select name="srt">
        <option value="0" <?php if ($_smarty_tpl->tpl_vars['ns']->value['selectedFilterSortBy']==0) {?>selected<?php }?>>None</option>
        <?php
$_from = $_smarty_tpl->tpl_vars['ns']->value['sortFields'];
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['fieldDisplayName'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['fieldDisplayName']->_loop = false;
$_smarty_tpl->tpl_vars['fieldName'] = new Smarty_Variable;
foreach ($_from as $_smarty_tpl->tpl_vars['fieldName']->value => $_smarty_tpl->tpl_vars['fieldDisplayName']->value) {
$_smarty_tpl->tpl_vars['fieldDisplayName']->_loop = true;
$foreachItemSav = $_smarty_tpl->tpl_vars['fieldDisplayName'];
?>
            <option value="<?php echo $_smarty_tpl->tpl_vars['fieldName']->value;?>
" <?php if ($_smarty_tpl->tpl_vars['ns']->value['selectedFilterSortBy']==$_smarty_tpl->tpl_vars['fieldName']->value) {?>selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['fieldDisplayName']->value;?>
</option>
        <?php
$_smarty_tpl->tpl_vars['fieldDisplayName'] = $foreachItemSav;
}
?>
    </select>
    <select name="ascdesc">
        <option value="ASC" <?php if ($_smarty_tpl->tpl_vars['ns']->value['selectedFilterSortByAscDesc']=='ASC') {?>selected<?php }?>>ASC</option>
        <option value="DESC" <?php if ($_smarty_tpl->tpl_vars['ns']->value['selectedFilterSortByAscDesc']=='DESC') {?>selected<?php }?>>DESC</option>
    </select>
</form>
<?php }
}
?>