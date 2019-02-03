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
            <footer class="footer">
                {include file="./util/footer.tpl"}
            </footer>
        </div>
        {if $ns.userType == $ns.userTypeAdmin}
            <div id="sticky_note" title="Sticky Note">
                <textarea id="sticky_note_content" rows="10" style="width: 100%; height: 100%">{$ns.sticky_note}</textarea>
            </div>
        {/if}
    </body>
</html>
