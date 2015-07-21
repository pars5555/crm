<?php /* Smarty version 3.1.23, created on 2015-07-21 10:28:51
         compiled from "D:/xampp/htdocs/crm/templates/main/payments.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:1194155ae02c346b981_60731252%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e84d878048a8f478d42276f369fcb0883c8a6e2a' => 
    array (
      0 => 'D:/xampp/htdocs/crm/templates/main/payments.tpl',
      1 => 1437467329,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1194155ae02c346b981_60731252',
  'variables' => 
  array (
    'ns' => 0,
    'SITE_PATH' => 0,
    'date' => 0,
    'time' => 0,
    'p' => 0,
    'selectedPartnerId' => 0,
    'pm' => 0,
    'selectedPaymentMethodId' => 0,
    'c' => 0,
    'selectedCurrencyId' => 0,
    'payment' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.23',
  'unifunc' => 'content_55ae02c35866e6_14849417',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_55ae02c35866e6_14849417')) {
function content_55ae02c35866e6_14849417 ($_smarty_tpl) {
if (!is_callable('smarty_function_html_select_date')) require_once 'D:/xampp/htdocs/crm/classes/lib/smarty/plugins/function.html_select_date.php';
if (!is_callable('smarty_function_html_select_time')) require_once 'D:/xampp/htdocs/crm/classes/lib/smarty/plugins/function.html_select_time.php';
?>
<?php
$_smarty_tpl->properties['nocache_hash'] = '1194155ae02c346b981_60731252';
?>
<div>
    <?php ob_start();?><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['getTemplateDir'][0][0]->getSmartyTemplateDir(array(),$_smarty_tpl);?>
<?php $_tmp1=ob_get_clean();?><?php echo $_smarty_tpl->getSubTemplate ($_tmp1."/main/left_menu.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0);
?>

    <?php if (isset($_smarty_tpl->tpl_vars['ns']->value['error_message'])) {?>
        <div>
            <span style="color:red"><?php echo $_smarty_tpl->tpl_vars['ns']->value['error_message'];?>
</span>
        </div>
    <?php }?>
    <?php if (isset($_smarty_tpl->tpl_vars['ns']->value['success_message'])) {?>
        <div>
            <span style="color:green"><?php echo $_smarty_tpl->tpl_vars['ns']->value['success_message'];?>
</span>
        </div>
    <?php }?>
    <div>
        <a class="button" href="javascript:void(0);">create</a>
        <a class="button" href="javascript:void(0);">cancel</a>
    </div>
    <form class="createSaleOrder" autocomplete="off" method="post" action="<?php echo $_smarty_tpl->tpl_vars['SITE_PATH']->value;?>
/dyn/main/do_create_payment">
        <div>
            <label>Payment Date</label>
            <?php $_smarty_tpl->tpl_vars['date'] = new Smarty_Variable('null', null, 0);?>
            <?php if (isset($_smarty_tpl->tpl_vars['ns']->value['req']['paymentDateYear'])) {?>
                <?php $_smarty_tpl->tpl_vars['date'] = new Smarty_Variable(((string)$_smarty_tpl->tpl_vars['ns']->value['req']['paymentDateYear'])."-".((string)$_smarty_tpl->tpl_vars['ns']->value['req']['paymentDateMonth'])."-".((string)$_smarty_tpl->tpl_vars['ns']->value['req']['paymentDateDay']), null, 0);?>
            <?php }?>
            <?php echo smarty_function_html_select_date(array('prefix'=>'paymentDate','start_year'=>2010,'end_year'=>2020,'time'=>$_smarty_tpl->tpl_vars['date']->value),$_smarty_tpl);?>

            <?php $_smarty_tpl->tpl_vars['time'] = new Smarty_Variable('null', null, 0);?>
            <?php if (isset($_smarty_tpl->tpl_vars['ns']->value['req']['paymentTimeHour'])) {?>
                <?php $_smarty_tpl->tpl_vars['time'] = new Smarty_Variable(((string)$_smarty_tpl->tpl_vars['ns']->value['req']['paymentTimeHour']).":".((string)$_smarty_tpl->tpl_vars['ns']->value['req']['paymentTimeMinute']), null, 0);?>
            <?php }?>
            <?php echo smarty_function_html_select_time(array('prefix'=>'paymentTime','display_seconds'=>false,'time'=>$_smarty_tpl->tpl_vars['time']->value),$_smarty_tpl);?>

        </div>
        <div>
            <label>Partner</label>
            <select name="partnerId">
                <?php if (isset($_smarty_tpl->tpl_vars['ns']->value['req']['partnerId'])) {?>
                    <?php $_smarty_tpl->tpl_vars['selectedPartnerId'] = new Smarty_Variable($_smarty_tpl->tpl_vars['ns']->value['req']['partnerId'], null, 0);?>
                <?php } else { ?>
                    <?php $_smarty_tpl->tpl_vars['selectedPartnerId'] = new Smarty_Variable('null', null, 0);?>
                <?php }?>
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
" <?php if (isset($_smarty_tpl->tpl_vars['selectedPartnerId']->value)&&$_smarty_tpl->tpl_vars['selectedPartnerId']->value==$_smarty_tpl->tpl_vars['p']->value->getId()) {?>selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['p']->value->getName();?>
</option>
                <?php
$_smarty_tpl->tpl_vars['p'] = $foreachItemSav;
}
?>
            </select>
        </div>
        <div>
            <label>Payment Method</label>
            <select name="paymentMethodId">
                <?php if (isset($_smarty_tpl->tpl_vars['ns']->value['req']['paymentMethodId'])) {?>
                    <?php $_smarty_tpl->tpl_vars['selectedPaymentMethodId'] = new Smarty_Variable($_smarty_tpl->tpl_vars['ns']->value['req']['paymentMethodId'], null, 0);?>
                <?php } else { ?>
                    <?php $_smarty_tpl->tpl_vars['selectedPaymentMethodId'] = new Smarty_Variable($_smarty_tpl->tpl_vars['ns']->value['defaultPaymentMethodId'], null, 0);?>
                <?php }?>
                <?php
$_from = $_smarty_tpl->tpl_vars['ns']->value['payment_methods'];
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['pm'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['pm']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['pm']->value) {
$_smarty_tpl->tpl_vars['pm']->_loop = true;
$foreachItemSav = $_smarty_tpl->tpl_vars['pm'];
?>
                    <option <?php if ($_smarty_tpl->tpl_vars['pm']->value->getId()==$_smarty_tpl->tpl_vars['selectedPaymentMethodId']->value) {?>selected<?php }?>
                                                                         value="<?php echo $_smarty_tpl->tpl_vars['pm']->value->getId();?>
"><?php echo $_smarty_tpl->tpl_vars['pm']->value->getName();?>
</option>
                <?php
$_smarty_tpl->tpl_vars['pm'] = $foreachItemSav;
}
?>
            </select>
        </div>
        <div>
            <label>Currency</label>
            <select name="currencyId">
                <?php if (isset($_smarty_tpl->tpl_vars['ns']->value['req']['currencyId'])) {?>
                    <?php $_smarty_tpl->tpl_vars['selectedCurrencyId'] = new Smarty_Variable($_smarty_tpl->tpl_vars['ns']->value['req']['currencyId'], null, 0);?>
                <?php } else { ?>
                    <?php $_smarty_tpl->tpl_vars['selectedCurrencyId'] = new Smarty_Variable($_smarty_tpl->tpl_vars['ns']->value['defaultCurrencyId'], null, 0);?>
                <?php }?>
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
                    <option <?php if ($_smarty_tpl->tpl_vars['c']->value->getId()==$_smarty_tpl->tpl_vars['selectedCurrencyId']->value) {?>selected<?php }?>
                                                                   value="<?php echo $_smarty_tpl->tpl_vars['c']->value->getId();?>
"><?php echo $_smarty_tpl->tpl_vars['c']->value->getName();?>
 (<?php echo $_smarty_tpl->tpl_vars['c']->value->getIso();?>
 <?php echo $_smarty_tpl->tpl_vars['c']->value->getTemplateChar();?>
)</option>
                <?php
$_smarty_tpl->tpl_vars['c'] = $foreachItemSav;
}
?>
            </select>
        </div>
        <div>
            <label>Amount</label>
            <input type="number" step="0.01" name="amount"/>
        </div>
        <div>
            <label>Note</label>
            <textarea  name="note"></textarea>
        </div>
        <input type="submit" value="Create"/>

    </form>

    <div>
        <div>
            <label>Filter Partner</label>
            <select name="filterPartnerId">
                <?php if (isset($_smarty_tpl->tpl_vars['ns']->value['req']['partnerId'])) {?>
                    <?php $_smarty_tpl->tpl_vars['selectedFilterPartnerId'] = new Smarty_Variable($_smarty_tpl->tpl_vars['ns']->value['req']['filterPartnerId'], null, 0);?>
                <?php } else { ?>
                    <?php $_smarty_tpl->tpl_vars['selectedFilterPartnerId'] = new Smarty_Variable('null', null, 0);?>
                <?php }?>
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
" <?php if (isset($_smarty_tpl->tpl_vars['selectedPartnerId']->value)&&$_smarty_tpl->tpl_vars['selectedPartnerId']->value==$_smarty_tpl->tpl_vars['p']->value->getId()) {?>selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['p']->value->getName();?>
</option>
                <?php
$_smarty_tpl->tpl_vars['p'] = $foreachItemSav;
}
?>
            </select>
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
                <?php if ($_smarty_tpl->tpl_vars['payment']->value->getCurrencyDto()->getSymbolPosition()=='left') {?>
                <span> <?php echo $_smarty_tpl->tpl_vars['payment']->value->getCurrencyDto()->getTemplateChar();?>
 </span>
                <?php }?>
                <span> <?php echo $_smarty_tpl->tpl_vars['payment']->value->getAmount();?>
 </span>
                <?php if ($_smarty_tpl->tpl_vars['payment']->value->getCurrencyDto()->getSymbolPosition()=='right') {?>
                <span> <?php echo $_smarty_tpl->tpl_vars['payment']->value->getCurrencyDto()->getTemplateChar();?>
 </span>
                <?php }?>
            </div>
        <?php
$_smarty_tpl->tpl_vars['payment'] = $foreachItemSav;
}
?>

    </div>
</div>
<?php }
}
?>