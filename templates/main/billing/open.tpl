<div class="container billing--open--container">
    <h1>Billing Order View</h1>
    {if isset($ns.error_message)}
        <div>
            <span style="color:red">{$ns.error_message}</span>
        </div>
    {/if}
    {if isset($ns.success_message)}
        <div>
            <span style="color:green">{$ns.success_message}</span>
        </div>
    {/if}
    {if isset($ns.billing)}
        <div>
            id: {$ns.billing->getId()}
        </div>
        <div>
            date: {$ns.billing->getDate()}
        </div>
        <div>
            billing method: {$ns.lm->getPhrase($ns.billing->getPaymentMethodDto()->getTranslationId())}
        </div>
        <div>
            Partner : {$ns.billing->getPartnerDto()->getName()}
        </div>
        <div>
            Amount : 
            {if $billing->getCurrencyDto()->getSymbolPosition() == 'left'}
                {$billing->getCurrencyDto()->getTemplateChar()}
            {/if}
            {-$billing->getAmount()}
            {if $billing->getCurrencyDto()->getSymbolPosition() == 'right'}
                {$billing->getCurrencyDto()->getTemplateChar()}
            {/if}
        </div>
        {if $billing->getCancelled() == 0}
            <form action="{SITE_PATH}/dyn/main_billing/do_cancel_billing">
                <input type="hidden" name="id" value="{$ns.billing->getId()}"/>
                <div>
                    <label>Note</label>
                    <textarea  name="note"></textarea>
                </div>
                <a id="cancelBillingButton" href="javascript:void(0);">Cancel</a>
            </form>
        {else}
            Cancelled
        {/if}
    {/if}
</div>
