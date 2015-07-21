<?php /* Smarty version 3.1.23, created on 2015-07-17 11:19:14
         compiled from "D:/xampp/htdocs/crm/templates/main/sales.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:1706455a8c8926e5912_46842002%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd35158b177ee96108f0dca5d6c53e3a06e557ee4' => 
    array (
      0 => 'D:/xampp/htdocs/crm/templates/main/sales.tpl',
      1 => 1437124751,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1706455a8c8926e5912_46842002',
  'variables' => 
  array (
    'ns' => 0,
    'p' => 0,
    'pm' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.23',
  'unifunc' => 'content_55a8c89272bed9_93492745',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_55a8c89272bed9_93492745')) {
function content_55a8c89272bed9_93492745 ($_smarty_tpl) {
if (!is_callable('smarty_function_html_select_date')) require_once 'D:/xampp/htdocs/crm/classes/lib/smarty/plugins/function.html_select_date.php';
?>
<?php
$_smarty_tpl->properties['nocache_hash'] = '1706455a8c8926e5912_46842002';
?>
<div>
    <?php ob_start();?><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['getTemplateDir'][0][0]->getSmartyTemplateDir(array(),$_smarty_tpl);?>
<?php $_tmp1=ob_get_clean();?><?php echo $_smarty_tpl->getSubTemplate ($_tmp1."/main/left_menu.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0);
?>

    <div>
        <a class="button" href="javascript:void(0);">create</a>
        <a class="button" href="javascript:void(0);">cancel</a>
    </div>
    <form class="createSaleOrder" autocomplete="off">
        <div>
            <label>Order Date</label>
            <?php echo smarty_function_html_select_date(array('prefix'=>'orderDate'),$_smarty_tpl);?>

        </div>
        <div>
            <label>Partner</label>
            <select>
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
                    <option><?php echo $_smarty_tpl->tpl_vars['p']->value->getName();?>
</option>
                <?php
$_smarty_tpl->tpl_vars['p'] = $foreachItemSav;
}
?>
            </select>
        </div>
        <div>
            <label>Payment</label>
            <select>
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
                    <option><?php echo $_smarty_tpl->tpl_vars['pm']->value->getName();?>
</option>
                <?php
$_smarty_tpl->tpl_vars['pm'] = $foreachItemSav;
}
?>
            </select>
        </div>
        <div>
            <label>Note</label>
            <textarea type="text" name="note"></textarea>
        </div>
        <div class="saleOrderLine">
            <table border="3" style="width: 500px">
                <thead>
                <th>Heading 1</th>
                <th>Heading 2</th>
                </thead>
                <tbody>
                    <tr>
                        <td></td>
                        <td ></td>
                    </tr>

                </tbody>
            </table>
        </div>
        <input type="submit" value="Create"/>
    </form>
</div>
<?php }
}
?>