<div class="container payment--list--container">
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

    {include file="{getTemplateDir}/main/payment/list_filters.tpl"}

    <div class="table_striped">
        <div class="table_header_group">
            <span class="table-cell"> ID </span>
            <span class="table-cell"> DATE </span>
            <span class="table-cell"> Partner </span>
            <span class="table-cell"> Payment Method </span>
            <span class="table-cell"> Amount </span>
            <span class="table-cell"> View </span>
        </div> 
        {foreach from=$ns.payments item=payment}
            <div class="table-row" href="{SITE_PATH}/payment/{$payment->getId()}">
                <span class="table-cell">{$payment->getId()} </span>
                <span class="table-cell"> {$payment->getDate()} </span>
                <span class="table-cell"> {$payment->getPartnerDto()->getName()} </span>
                <span class="table-cell"> {$payment->getPaymentMethodDto()->getName()} </span>
                <span class="table-cell"> 
                    {if $payment->getCurrencyDto()->getSymbolPosition() == 'left'}
                        {$payment->getCurrencyDto()->getTemplateChar()}
                    {/if}
                    {$payment->getAmount()}
                    {if $payment->getCurrencyDto()->getSymbolPosition() == 'right'}
                        {$payment->getCurrencyDto()->getTemplateChar()}
                    {/if}
                </span>
                <a class="table-cell view_item" href="{SITE_PATH}/payment/{$payment->getId()}">
                    <span class="button blue">open</span>
                </a>
            </div>
        {/foreach}


    </div>

    <a class="button blue" href="{SITE_PATH}/payment/create">create</a>

</div>