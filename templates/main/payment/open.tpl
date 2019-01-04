<div class="container payment--open--container">
    <h1 class="main_title">Payment Order View</h1>

    {if isset($ns.error_message)}
        {include file="{ngs cmd=get_template_dir}/main/message.tpl" type="error" content="{$ns.error_message}"} 
    {/if}
    {if isset($ns.success_message)}
        {include file="{ngs cmd=get_template_dir}/main/message.tpl" type="success" content="{$ns.success_message}"} 
    {/if}

    {if isset($ns.payment)}
        <div class="table_striped table_striped_simple">
            <div class="table-row">
                <span class="table-cell">
                    id :
                </span>
                <span class="table-cell">
                    {$ns.payment->getId()}
                </span>
            </div>
            <div class="table-row">
                <span class="table-cell">
                    date :
                </span>
                <span class="table-cell">
                    {$ns.payment->getDate()}
                </span>
            </div>
            <div class="table-row">
                <span class="table-cell">
                    payment method :
                </span>
                <span class="table-cell">
                    {$ns.lm->getPhrase($ns.payment->getPaymentMethodDto()->getTranslationId())}
                </span>
            </div>
            <div class="table-row">
                <span class="table-cell">
                    Partner :
                </span>
                <span class="table-cell">
                    {$ns.payment->getPartnerDto()->getName()}
                </span>
            </div>
            <div class="table-row">
                <span class="table-cell">
                    Amount : 
                </span>
                <span class="table-cell">
                    {if $ns.payment->getCurrencyDto()->getSymbolPosition() == 'left'}
                        {$ns.payment->getCurrencyDto()->getTemplateChar()}
                    {/if}
                    {$ns.payment->getAmount()}
                    {if $ns.payment->getCurrencyDto()->getSymbolPosition() == 'right'}
                        {$ns.payment->getCurrencyDto()->getTemplateChar()}
                    {/if}
                </span>
            </div>
            <div class="table-row">
                <span class="table-cell">
                    Signature:
                </span>
                <span class="table-cell">
                    <img id="signature" style="width: 200px"/>
                </span>
            </div>
            <div class="table-row">
                <span class="table-cell">
                    Note :
                </span>
                <span class="table-cell">
                    {$ns.payment->getNote()}
                </span>
            </div>
        </div>
        {if $payment->getCancelled() == 0}
            <form action="{$SITE_PATH}/dyn/main_payment/do_cancel_payment">
                <input type="hidden" name="id" value="{$ns.payment->getId()}"/>
                <div class="form-group">
                    <label class="label">Note</label>
                    <textarea class="text" name="note"></textarea>
                </div>
                <a class="button blue" id="cancelPaymentButton" href="javascript:void(0);">Cancel</a>
            </form>
        {else}
            <a class="button blue" href="{$SITE_PATH}/dyn/main_payment/do_restore_payment?id={$ns.payment->getId()}">
                <span>Restore</span>
            </a>
            <a class="button blue" href="{$SITE_PATH}/dyn/main_payment/do_delete_payment?id={$ns.payment->getId()}">
                <span>Delete</span>
            </a>
        {/if}
    {/if}
</div>

<div id="signatureContainer" style="width: 500px;color:#0f60a7;visibility: hidden; margin: 0 auto;">
    <span class="hidden">{$ns.payment->getSignature()}</span>
</div>
{include file="{ngs cmd=get_template_dir}/main/util/attachments.tpl"} 

<form id="upload_attachment_form" target="upload_target" enctype="multipart/form-data" method="post" action="{$SITE_PATH}/dyn/attachment/do_upload" autocomplete="off">
    <a class="button blue" id="select_attachment_button" >select attachment...</a>
    <input type="hidden" name="entity_id" value="{$ns.payment->getId()}"/>
    <input type="hidden" name="entity_name" value="payment"/>
    <input type="hidden" name="partner_id" value="{$ns.payment->getPartnerId()}"/>
    <input id="file_input" name="file" type="file" style="display:none" />
</form>
<iframe id="upload_target" name="upload_target" style="width:0;height:0;border:0px solid #fff;display: none;" ></iframe>