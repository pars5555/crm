<?php /* Smarty version 3.1.23, created on 2015-07-21 11:11:18
         compiled from "D:/xampp/htdocs/crm/templates/main/payment/payments_list.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:879355ae0cb62ee5d9_09507340%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '466c9150bade35943437d147a49586b82605bf01' => 
    array (
      0 => 'D:/xampp/htdocs/crm/templates/main/payment/payments_list.tpl',
      1 => 1437469874,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '879355ae0cb62ee5d9_09507340',
  'variables' => 
  array (
    'ns' => 0,
    'payment' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.23',
  'unifunc' => 'content_55ae0cb634bed4_49481386',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_55ae0cb634bed4_49481386')) {
function content_55ae0cb634bed4_49481386 ($_smarty_tpl) {
?>
<?php
$_smarty_tpl->properties['nocache_hash'] = '879355ae0cb62ee5d9_09507340';
?>
<div>
    <?php ob_start();?><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['getTemplateDir'][0][0]->getSmartyTemplateDir(array(),$_smarty_tpl);?>
<?php $_tmp1=ob_get_clean();?><?php echo $_smarty_tpl->getSubTemplate ($_tmp1."/main/payment/payments_list_filters.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0);
?>

    <div>
        <span> ID </span>
        <span> DATE </span>
        <span> Partner </span>
        <span> Payment Method </span>
        <span> Amount </span>
    </div> 
    <?php
$_from = $_smarty_tpl->tpl_vars['ns']->value['payments'];
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['payment'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['payment']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['payment']->value) {
$_smarty_tpl->tpl_vars['payment']->_loop = true;
$foreachItemSav = $_smarty_tpl->tpl_vars['payment'];
?>
        <div>
            <span> <?php echo $_smarty_tpl->tpl_vars['payment']->value->getId();?>
 </span>
            <span> <?php echo $_smarty_tpl->tpl_vars['payment']->value->getDate();?>
 </span>
            <span> <?php echo $_smarty_tpl->tpl_vars['payment']->value->getPartnerDto()->getName();?>
 </span>
            <span> <?php echo $_smarty_tpl->tpl_vars['payment']->value->getPaymentMethodDto()->getName();?>
 </span>
            <span> 
                <?php if ($_smarty_tpl->tpl_vars['payment']->value->getCurrencyDto()->getSymbolPosition()=='left') {?>
                    <?php echo $_smarty_tpl->tpl_vars['payment']->value->getCurrencyDto()->getTemplateChar();?>

                <?php }?>
                <?php echo $_smarty_tpl->tpl_vars['payment']->value->getAmount();?>

                <?php if ($_smarty_tpl->tpl_vars['payment']->value->getCurrencyDto()->getSymbolPosition()=='right') {?>
                    <?php echo $_smarty_tpl->tpl_vars['payment']->value->getCurrencyDto()->getTemplateChar();?>

                <?php }?>
            </span>

        </div>
    <?php
$_smarty_tpl->tpl_vars['payment'] = $foreachItemSav;
}
?>

</div><?php }
}
?>