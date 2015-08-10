<div class="container purchase--list--container">
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

    {include file="{getTemplateDir}/main/purchase/list_filters.tpl"}

    <div class="table_striped">
        <div class="table_header_group">
            <span class="table-cell"> ID </span>
            <span class="table-cell"> Partner </span>
            <span class="table-cell"> Date</span>
            <span class="table-cell"> Total Amount</span>
            <span class="table-cell"> Note </span>
            <span class="table-cell"> View </span>
        </div> 
        {foreach from=$ns.purchaseOrders item=purchaseOrder}
            <div class="table-row" {if $purchaseOrder->getCancelled() == 1}style="background: red"{/if}>
                <a class="table-cell view_item" href="{SITE_PATH}/purchase/{$purchaseOrder->getId()}">
                    <span class="table-cell">{$purchaseOrder->getId()} </span>
                </a>
                <span class="table-cell"> {$purchaseOrder->getPartnerDto()->getName()} </span>
                <span class="table-cell"> {$purchaseOrder->getOrderDate()} </span>

                {assign totalAmount $purchaseOrder->getTotalAmount()}
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
                <span class="table-cell"> {$purchaseOrder->getNote()} </span>
                <a class="table-cell view_item" href="{SITE_PATH}/purchase/{$purchaseOrder->getId()}">
                    <span class="button blue">open</span>
                </a>
                <a class="table-cell view_item deletePurchaseOrder"  href="{SITE_PATH}/dyn/main_purchase/do_delete_purchase_order?id={$purchaseOrder->getId()}">
                    <span class="button blue">delete</span>
                </a>
            </div>
        {/foreach}
    </div>
    <a  href="{SITE_PATH}/purchase/create"><img src="{SITE_PATH}/img/new_order.png"/></a>
</div>