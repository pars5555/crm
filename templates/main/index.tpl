<!DOCTYPE html>
<html lang="en">
    <head>
        {include file="./util/header_control.tpl"}
    </head>
    <body>
        <div id="ajaxLoader"></div>
            
        <header class="header">
            {include file="./util/header.tpl"}
        </header>
        <section class="wrapper">
            {include file="./util/left_menu.tpl"}
            <div class="content" id="indexRightContent">
                {nest ns=content}
            </div>
        </section>
        <footer class="footer">
            {include file="./util/footer.tpl"}
        </footer>
    </body>
</html>
