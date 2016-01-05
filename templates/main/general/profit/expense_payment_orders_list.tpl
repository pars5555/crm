<div class="container sale--list--container">
    <div class="table_striped">
        <div class="table_header_group">
            <span class="table-cell"> ID </span>
            <span class="table-cell"> Partner </span>
            <span class="table-cell"> Date</span>
            <span class="table-cell"> Amount </span>
            <span class="table-cell"> Note </span>
        </div> 
        {foreach from=$ns.expensePaymentOrders item=saleOrder}
            <div class="table-row" {if $saleOrder->getCancelled() == 1}style="background: red"{/if}>
                <a class="table-cell" href="{$SITE_PATH}/sale/{$saleOrder->getId()}">
                    <span>{$saleOrder->getId()} </span>
                </a>
                <span class="table-cell"> {$saleOrder->getPartnerDto()->getName()} </span>
                <span class="table-cell"> {$saleOrder->getDate()} </span>

                <span class="table-cell">
                    <span class="price">
                        {assign currencyDto $ns.currencies[$saleOrder->getCurrencyId()]}
                        {if $currencyDto->getSymbolPosition() == 'left'}
                            {$currencyDto->getTemplateChar()}
                        {/if}
                        {$saleOrder->getAmount()}
                        {if $currencyDto->getSymbolPosition() == 'right'}
                            {$currencyDto->getTemplateChar()}
                        {/if}
                    </span>
                </span>
                <span class="table-cell"> {$saleOrder->getNote()} </span>

            </div>
        {/foreach}
    </div>



</div>