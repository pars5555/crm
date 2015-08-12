<div class="container payment--open--container">
    <h1>Payment Order View</h1>
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
    {if isset($ns.payment)}
        <div>
            id: {$ns.payment->getId()}
        </div>
        <div>
            date: {$ns.payment->getDate()}
        </div>
        <div>
            payment method: {$ns.lm->getPhrase($ns.payment->getPaymentMethodDto()->getTranslationId())}
        </div>
        <div>
            Partner : {$ns.payment->getPartnerDto()->getName()}
        </div>
        <div>
            Amount : 
            {if $payment->getCurrencyDto()->getSymbolPosition() == 'left'}
                {$payment->getCurrencyDto()->getTemplateChar()}
            {/if}
            {$payment->getAmount()}
            {if $payment->getCurrencyDto()->getSymbolPosition() == 'right'}
                {$payment->getCurrencyDto()->getTemplateChar()}
            {/if}
        </div>
        <div>
            Note : {$ns.payment->getNote()}
        </div>
        {if $payment->getCancelled() == 0}
            <form action="{SITE_PATH}/dyn/main_payment/do_cancel_payment">
                <input type="hidden" name="id" value="{$ns.payment->getId()}"/>
                <div>
                    <label>Note</label>
                    <textarea  name="note"></textarea>
                </div>
                <a id="cancelPaymentButton" href="javascript:void(0);">Cancel</a>
            </form>
        {else}
            <a class="table-cell view_item"  href="{SITE_PATH}/dyn/main_payment/do_restore_payment?id={$ns.payment->getId()}">
                <span class="button blue">Restore</span>
            </a>
        {/if}
    {/if}
</div>
