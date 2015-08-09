<div class="container sale--list--container">
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

    {include file="{getTemplateDir}/main/sale/list_filters.tpl"}

    <div class="table_striped">
        <div class="table_header_group">
            <span class="table-cell"> ID </span>
            <span class="table-cell"> Partner </span>
            <span class="table-cell"> Date</span>
            <span class="table-cell"> Total Amount</span>
            <span class="table-cell"> Note </span>
            <span class="table-cell"> View </span>
        </div> 
        {foreach from=$ns.saleOrders item=saleOrder}
            <div class="table-row" {if $saleOrder->getCancelled() == 1}style="background: red"{/if}>
                <a class="table-cell view_item" href="{SITE_PATH}/sale/{$saleOrder->getId()}">
                    <span class="table-cell">{$saleOrder->getId()} </span>
                </a>
                <span class="table-cell"> {$saleOrder->getPartnerDto()->getName()} </span>
                <span class="table-cell"> {$saleOrder->getOrderDate()} </span>

                {assign totalAmount $saleOrder->getTotalAmount()}
                <span class="table-cell">
                    {foreach from=$totalAmount key=currencyId item=amount}
                        <span class="price">
                            {assign currencyDto $ns.currencies[$currencyId]}
                            {if $currencyDto->getSymbolPosition() == 'left'}
                                {$currencyDto->getTemplateChar()}
                            {/if}
                            {$amount}
                            {if $currencyDto->getSymbolPosition() == 'right'}
                                {$currencyDto->getTemplateChar()}
                            {/if}
                        </span>
                    {/foreach}
                </span>
                <span class="table-cell"> {$saleOrder->getNote()} </span>
                <a class="table-cell view_item" href="{SITE_PATH}/sale/{$saleOrder->getId()}">
                    <span class="button blue">open</span>
                </a>
                <a class="table-cell view_item deleteSaleOrder"  href="{SITE_PATH}/dyn/main_sale/do_delete_sale_order?id={$saleOrder->getId()}">
                    <span class="button blue">delete</span>
                </a>
            </div>
        {/foreach}
    </div>

    <a href="{SITE_PATH}/sale/create"><img src="{SITE_PATH}/img/new_order.png"/></a>

</div>