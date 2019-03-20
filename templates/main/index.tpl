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


                <div id="confirm_modalBox" class="modal modal-medium">
                    <div class="modal-container">
                        <div class="modal-inner-container ">
                            <span id='popup_close_btn' class="modal-close f_modal-close">
                                <span class="close-icon1"></span>
                                <span class="close-icon2"></span>
                            </span>
                            <h1 class="modal-headline" id='confirm_dlg_headline'></h1>
                            <h3 id='confirm_dlg_message'></h3>
                            <a href="javascript:void(0);" id="popup_confirm_btn" class="button blue">Confirm</a>
                            <a href="javascript:void(0);" id="popup_cancel_btn" class="button grey">Cancel</a>
                            <input id="confirm_item_id" type="hidden"/>
                            <input id="confirm_tmp_val" type="hidden"/>
                        </div>
                    </div>
                </div>
            </section>
            <footer class="footer">
                {include file="./util/footer.tpl"}
            </footer>
        </div>
        {if $ns.userType == $ns.userTypeAdmin}
            <div id="sticky_note" title="Sticky Note">
                <textarea id="sticky_note_content" data-page_name="{$ns.sticky_note_page_name}" rows="10" style="width: 100%; height: 100%">{$ns.sticky_note}</textarea>
            </div>
        {/if}
    </body>
</html>
