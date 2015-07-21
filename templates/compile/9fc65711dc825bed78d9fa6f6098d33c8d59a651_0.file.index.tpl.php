<?php /* Smarty version 3.1.23, created on 2015-07-21 17:13:15
         compiled from "D:/xampp/htdocs/crm/templates/main/index.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:1687855ae618b86e198_96222844%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9fc65711dc825bed78d9fa6f6098d33c8d59a651' => 
    array (
      0 => 'D:/xampp/htdocs/crm/templates/main/index.tpl',
      1 => 1437491555,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1687855ae618b86e198_96222844',
  'variables' => 
  array (
    'STATIC_PATH' => 0,
    'VERSION' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.23',
  'unifunc' => 'content_55ae618b8a1d83_31719221',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_55ae618b8a1d83_31719221')) {
function content_55ae618b8a1d83_31719221 ($_smarty_tpl) {
if (!is_callable('smarty_function_nest')) require_once 'D:/xampp/htdocs/crm/classes/lib/smarty/plugins/function.nest.php';
?>
<?php
$_smarty_tpl->properties['nocache_hash'] = '1687855ae618b86e198_96222844';
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>CRM</title>
        <link href="<?php echo $_smarty_tpl->tpl_vars['STATIC_PATH']->value;?>
/css/out/styles.css" type="text/css" rel="stylesheet prefetch">
        <?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['STATIC_PATH']->value;?>
/js/out/ngs.js?<?php echo $_smarty_tpl->tpl_vars['VERSION']->value;?>
"><?php echo '</script'; ?>
>
        <?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['STATIC_PATH']->value;?>
/js/out/ngs_loads.js?<?php echo $_smarty_tpl->tpl_vars['VERSION']->value;?>
"><?php echo '</script'; ?>
>
        <?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['STATIC_PATH']->value;?>
/js/out/ngs_actions.js?<?php echo $_smarty_tpl->tpl_vars['VERSION']->value;?>
"><?php echo '</script'; ?>
>
    </head>
    <body>
        <section id="main" class="content">
            <header>
                header
            </header>
        </section>
        <section class="content">
            <?php echo smarty_function_nest(array('ns'=>'nested_load'),$_smarty_tpl);?>

        </section>
        <footer>
            footer
        </footer>
    </body>
</html>
<?php }
}
?>