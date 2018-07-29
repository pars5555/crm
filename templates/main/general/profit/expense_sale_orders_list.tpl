<div class="container sale--list--container">
    <div class="table_striped">
        <div class="table_header_group">
            <span class="table-cell"> ID </span>
            <span class="table-cell"> Partner </span>
            <span class="table-cell"> Date</span>
            <span class="table-cell"> Billing Deadline </span>
            <span class="table-cell"> Total Amount </span>
            <span class="table-cell"> Total Profit </span>
            <span class="table-cell"> Note </span>
        </div> 
        {foreach from=$ns.expenseSaleOrders item=saleOrder}
            <div class="table-row" {if $saleOrder->getCancelled() == 1}style="color: gray"{/if}>
                <a class="table-cell" href="{$SITE_PATH}/sale/{$saleOrder->getId()}">
                    <span>{$saleOrder->getId()} </span>
                </a>
                <span class="table-cell"> {$saleOrder->getPartnerDto()->getName()} </span>
                <span class="table-cell"> {$saleOrder->getOrderDate()} </span>
                <span class="table-cell" style="{if $smarty.now|date_format:"%Y-%m-%d">=$saleOrder->getBillingDeadline() && $saleOrder->getBilled()==0}color:red{/if}"> {$saleOrder->getBillingDeadline()} </span>

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
                <span class="table-cell"> {$saleOrder->getTotalProfit()} </span>
                <span class="table-cell"> {$saleOrder->getNote()} </span>

            </div>
        {/foreach}
    </div>



</div>