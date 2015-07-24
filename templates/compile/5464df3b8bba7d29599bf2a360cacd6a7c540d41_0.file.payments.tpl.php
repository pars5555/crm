<?php /* Smarty version 3.1.23, created on 2015-07-23 10:36:07
         compiled from "D:/xampp/htdocs/crm/templates/main/payment/payments.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:1919855b0a777858245_53387643%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '5464df3b8bba7d29599bf2a360cacd6a7c540d41' => 
    array (
      0 => 'D:/xampp/htdocs/crm/templates/main/payment/payments.tpl',
      1 => 1437640543,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1919855b0a777858245_53387643',
  'variables' => 
  array (
    'ns' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.23',
  'unifunc' => 'content_55b0a77789dc46_23001634',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_55b0a77789dc46_23001634')) {
function content_55b0a77789dc46_23001634 ($_smarty_tpl) {
if (!is_callable('smarty_function_nest')) require_once 'D:/xampp/htdocs/crm/classes/lib/smarty/plugins/function.nest.php';
?>
<?php
$_smarty_tpl->properties['nocache_hash'] = '1919855b0a777858245_53387643';
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
        <a class="button" id="createPaymentButton" href="javascript:void(0);">create</a>
        <a class="button" id="cancelPaymentButton" href="javascript:void(0);">cancel</a>
    </div>
    <?php ob_start();?><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['getTemplateDir'][0][0]->getSmartyTemplateDir(array(),$_smarty_tpl);?>
<?php $_tmp2=ob_get_clean();?><?php echo $_smarty_tpl->getSubTemplate ($_tmp2."/main/payment/payment_create_form.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0);
?>


    <div>
        <?php echo smarty_function_nest(array('ns'=>'payment_list'),$_smarty_tpl);?>

    </div>
</div>
<?php }
}
?>