<div class="container payment--list--container">
    <h1>Payment Orders</h1>
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
    <a  href="{SITE_PATH}/payment/create"><img src="{SITE_PATH}/img/add.png"/></a>
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
            <div class="table-row" {if $payment->getCancelled() == 1}style="background: red"{/if} href="{SITE_PATH}/payment/{$payment->getId()}">
                <a class="table-cell view_item" href="{SITE_PATH}/payment/{$payment->getId()}">
                    <span class="table-cell">{$payment->getId()} </span>
                </a>
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
                {if $payment->getCancelled() == 1}
                    <a class="table-cell view_item deletePayment"  href="{SITE_PATH}/dyn/main_payment/do_delete_payment?id={$payment->getId()}">
                        <span class="button blue">delete</span>
                    </a>
                {/if}
            </div>
        {/foreach}


    </div>



</div>