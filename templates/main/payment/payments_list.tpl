<div>
    {include file="{getTemplateDir}/main/payment/payments_list_filters.tpl"}
    <div>
        <span> ID </span>
        <span> DATE </span>
        <span> Partner </span>
        <span> Payment Method </span>
        <span> Amount </span>
    </div> 
    {foreach from=$ns.payments item=payment}
        <div>
            <a href="{SITE_PATH}/payment/{$payment->getId()}">{$payment->getId()} </a>
            <span> {$payment->getDate()} </span>
            <span> {$payment->getPartnerDto()->getName()} </span>
            <span> {$payment->getPaymentMethodDto()->getName()} </span>
            <span> 
                {if $payment->getCurrencyDto()->getSymbolPosition() == 'left'}
                    {$payment->getCurrencyDto()->getTemplateChar()}
                {/if}
                {$payment->getAmount()}
                {if $payment->getCurrencyDto()->getSymbolPosition() == 'right'}
                    {$payment->getCurrencyDto()->getTemplateChar()}
                {/if}
            </span>

        </div>
    {/foreach}

</div>