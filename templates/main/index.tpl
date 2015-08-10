<!DOCTYPE html>
<html lang="en">
    <head>
        {include file="{getTemplateDir}/main/util/header_control.tpl"}
    </head>
    <body>
        <div id="ajaxLoader"></div>
            
        <header class="header">
            {include file="{getTemplateDir}/main/util/header.tpl"}
        </header>
        <section class="wrapper">
            {include file="{getTemplateDir}/main/util/left_menu.tpl"}
            <div class="content">
                {nest ns=content}
            </div>
        </section>
        <footer class="footer">
            {include file="{getTemplateDir}/main/util/footer.tpl"}
        </footer>
    </body>
</html>
