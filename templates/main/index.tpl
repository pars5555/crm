<!DOCTYPE html>
<html lang="en">
    <head>
        {include file="./util/header_control.tpl"}
    </head>
    <body>
        <div class="main-container" id="mainContainer">
            <div id="ajaxLoader"></div>

            <header class="header">
                {include file="./util/header.tpl"}
            </header>
            <section class="wrapper" id="mainWrapper">
                {if $ns.userType == $ns.userTypeAdmin}
                {include file="./util/left_menu.tpl"}
                {/if}
                <div class="content" id="indexRightContent">
                    {nest ns=content}
                </div>
            </section>
            {*<footer class="footer">*}
                {*{include file="./util/footer.tpl"}*}
            {*</footer>*}
        </div>
    </body>
</html>
