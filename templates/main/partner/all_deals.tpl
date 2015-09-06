<div class="container partner--list--container">
    <h1 class="main_title">{$ns.partner->getName()} All Deals</h1>

    <div class="table_striped">
        <div class="table_header_group">
            <span class="table-cell"> ID </span>
            <span class="table-cell"> Type </span>
            <span class="table-cell"> Date </span>
            <span class="table-cell"> Amount </span>
        </div> 
        {foreach from=$ns.allDeals item=deal}
            <div class="table-row">

                <a class="table-cell"  href="{$SITE_PATH}/{$deal[0]}/{$deal[1]->getId()}" target="_blank">
                    <span>{$deal[1]->getId()} </span>
                </a>
                <a class="table-cell" href="{$SITE_PATH}/{$deal[0]}/{$deal[1]->getId()}" target="_blank">
                    <span>{$deal[0]} </span>
                </a>
                <a class="table-cell">
                    {if $deal[0] == 'sale' || $deal[0] == 'purchase'}
                        <span>{$deal[1]->getOrderDate()} </span>
                    {else}
                        <span>{$deal[1]->getDate()} </span>
                    {/if}
                </a>
                <a class="table-cell">
                        <span>{$deal[1]->getNote()} </span>
                </a>
                <span class="table-cell">
                    {if $deal[0] == 'sale' || $deal[0] == 'purchase'}
                        {assign totalAmount $deal[1]->getTotalAmount()}
                        {foreach from=$totalAmount key=currencyId item=amount}
                            <span class="price">
                                {assign currencyDto $ns.currencies[$currencyId]}
                                {if $currencyDto->getSymbolPosition() == 'left'}
                                    {$currencyDto->getTemplateChar()}
                                {/if}
                                {$amount|number_format:2}
                                {if $currencyDto->getSymbolPosition() == 'right'}
                                    {$currencyDto->getTemplateChar()}
                                {/if}
                            </span>
                        {/foreach}

                    {else}
                        <span class="price">
                                {assign currencyDto $ns.currencies[$deal[1]->getCurrencyId()]}
                                {if $currencyDto->getSymbolPosition() == 'left'}
                                    {$currencyDto->getTemplateChar()}
                                {/if}
                                {$deal[1]->getAmount()|number_format:2}
                                {if $currencyDto->getSymbolPosition() == 'right'}
                                    {$currencyDto->getTemplateChar()}
                                {/if}
                            </span>
                        <span> </span>
                    {/if}
                </span>

            </div>
        {/foreach}
    </div>


</div>