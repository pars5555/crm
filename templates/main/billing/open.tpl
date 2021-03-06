<div class="container billing--open--container">
    <h1 class="main_title">Billing Order View</h1>

    {if isset($ns.error_message)}
        {include file="{ngs cmd=get_template_dir}/main/message.tpl" type="error" content="{$ns.error_message}"} 
    {/if}
    {if isset($ns.success_message)}
        {include file="{ngs cmd=get_template_dir}/main/message.tpl" type="success" content="{$ns.success_message}"} 
    {/if}

    {if isset($ns.billing)}
        <div class="table_striped table_striped_simple">
            <div class="table-row">
                <span class="table-cell">
                    id :
                </span>
                <span class="table-cell">
                    {$ns.billing->getId()}
                </span>
            </div>
            <div class="table-row">
                <span class="table-cell">
                    date :
                </span>
                <span class="table-cell">
                    {$ns.billing->getDate()}
                </span>
            </div>
            <div class="table-row">
                <span class="table-cell">
                    billing method :
                </span>
                <span class="table-cell">
                    {$ns.lm->getPhrase($ns.billing->getPaymentMethodDto()->getTranslationId())}
                </span>
            </div>
            <div class="table-row">
                <span class="table-cell">
                    Partner :
                </span>
                <span class="table-cell">
                    {$ns.billing->getPartnerDto()->getName()}
                </span>
            </div>
            <div class="table-row">
                <span class="table-cell">
                    Amount : 
                </span>
                <span class="table-cell">
                    {if $billing->getCurrencyDto()->getSymbolPosition() == 'left'}
                        {$billing->getCurrencyDto()->getTemplateChar()}
                    {/if}
                    {-$billing->getAmount()}
                    {if $billing->getCurrencyDto()->getSymbolPosition() == 'right'}
                        {$billing->getCurrencyDto()->getTemplateChar()}
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
                    {$ns.billing->getNote()}
                </span>
            </div>
        </div>
        {if $billing->getCancelled() == 0}
            <form action="{$SITE_PATH}/dyn/main_billing/do_cancel_billing">
                <input type="hidden" name="id" value="{$ns.billing->getId()}"/>
                <div class="form-group">
                    <label class="label">Note :</label>
                    <textarea class="text" name="note"></textarea>
                </div>
                <a class="button blue" id="cancelBillingButton" href="javascript:void(0);">Cancel</a>
            </form>
        {else}
            <a class="button blue"  href="{$SITE_PATH}/dyn/main_billing/do_restore_billing?id={$ns.billing->getId()}">
                <span>Restore</span>
            </a>
        {/if}
    {/if}
</div>

<div id="signatureContainer" style="width: 500px;color:#0f60a7;visibility: hidden; margin: 0 auto;">
    <span class="hidden">{$ns.billing->getSignature()}</span>
</div>
{include file="{ngs cmd=get_template_dir}/main/util/attachments.tpl"}
<form id="upload_attachment_form" target="upload_target" enctype="multipart/form-data" method="post" action="{$SITE_PATH}/dyn/attachment/do_upload" autocomplete="off">
    <a class="button blue" id="select_attachment_button" >select attachment...</a>
    <input type="hidden" name="entity_id" value="{$ns.billing->getId()}"/>
    <input type="hidden" name="entity_name" value="billing"/>
    <input type="hidden" name="partner_id" value="{$ns.billing->getPartnerId()}"/>
    <input id="file_input" name="file" type="file" style="display:none" />
</form>
<iframe id="upload_target" name="upload_target" style="width:0;height:0;border:0px solid #fff;display: none;" ></iframe>
