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

    <a class="button" href="{SITE_PATH}/sale/create">create</a>
    {include file="{getTemplateDir}/main/sale/list_filters.tpl"}
    <div>
        <span> ID </span>
        <span> Partner </span>
        <span> Date</span>
        <span> Total Amount</span>
        <span> Note </span>
    </div> 
    {foreach from=$ns.saleOrders item=saleOrder}
        <div>
            <a href="{SITE_PATH}/sale/{$saleOrder->getId()}">{$saleOrder->getId()} </a>
            <span> {$saleOrder->getPartnerDto()->getName()} </span>
            <span> {$saleOrder->getOrderDate()} </span>

            {assign totalAmount $saleOrder->getTotalAmount()}
            {foreach from=$totalAmount key=currencyId item=amount}
                <span>
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

            <span> {$saleOrder->getNote()} </span>
        </div>
    {/foreach}

</div>