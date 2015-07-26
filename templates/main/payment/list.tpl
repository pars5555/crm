<div>
    {include file="{getTemplateDir}/main/left_menu.tpl"}
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
    
    <a class="button" href="{SITE_PATH}/payment/create">create</a>
    {include file="{getTemplateDir}/main/payment/list_filters.tpl"}
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