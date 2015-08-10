<div class="container billing--list--container">
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

    {include file="{getTemplateDir}/main/billing/list_filters.tpl"}
    <a  href="{SITE_PATH}/billing/create"><img src="{SITE_PATH}/img/add.png"/></a>
    <div class="table_striped">
        <div class="table_header_group">
            <span class="table-cell"> ID </span>
            <span class="table-cell"> DATE </span>
            <span class="table-cell"> Partner </span>
            <span class="table-cell"> Billing Method </span>
            <span class="table-cell"> Amount </span>
            <span class="table-cell"> View </span>
        </div> 
        {foreach from=$ns.billings item=billing}
            <div class="table-row" {if $billing->getCancelled() == 1}style="background: red"{/if} href="{SITE_PATH}/billing/{$billing->getId()}">
                <a class="table-cell view_item" href="{SITE_PATH}/billing/{$billing->getId()}">
                    <span class="table-cell">{$billing->getId()} </span>
                </a>
                <span class="table-cell"> {$billing->getDate()} </span>
                <span class="table-cell"> {$billing->getPartnerDto()->getName()} </span>
                <span class="table-cell"> {$billing->getPaymentMethodDto()->getName()} </span>
                <span class="table-cell"> 
                    {if $billing->getCurrencyDto()->getSymbolPosition() == 'left'}
                        {$billing->getCurrencyDto()->getTemplateChar()}
                    {/if}
                    {-$billing->getAmount()}
                    {if $billing->getCurrencyDto()->getSymbolPosition() == 'right'}
                        {$billing->getCurrencyDto()->getTemplateChar()}
                    {/if}
                </span>
                <a class="table-cell view_item" href="{SITE_PATH}/billing/{$billing->getId()}">
                    <span class="button blue">open</span>
                </a>
                {if $billing->getCancelled() == 1}
                    <a class="table-cell view_item deleteBilling"  href="{SITE_PATH}/dyn/main_billing/do_delete_billing?id={$billing->getId()}">
                        <span class="button blue">delete</span>
                    </a>
                {/if}
            </div>
        {/foreach}


    </div>



</div>