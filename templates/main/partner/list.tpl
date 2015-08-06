<div class="container partner--list--container">
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

    {include file="{getTemplateDir}/main/partner/list_filters.tpl"}

    <div class="table_striped">
        <div class="table_header_group">
            <span class="table-cell"> ID </span>
            <span class="table-cell"> Name</span>
            <span class="table-cell"> Email </span>
            <span class="table-cell"> Address </span>
            <span class="table-cell"> Dept </span>
            <span class="table-cell"> Sale Orders</span>
            <span class="table-cell"> Purchase Orders</span>
            <span class="table-cell"> Payments Transactions </span>
            <span class="table-cell"> View </span>
        </div> 
        {foreach from=$ns.partners item=partner}
            <div class="table-row">
                <span class="table-cell">{$partner->getId()} </span>
                <span class="table-cell">{$partner->getName()} </span>
                <span class="table-cell"> {$partner->getEmail()} </span>
                <span class="table-cell"> {$partner->getAddress()} </span>
                <span  class="table-cell"> 
                    {if isset($partnersDept[$partner->getId()])}
                        {foreach from=$partnersDept[$partner->getId()] key=currencyId item=amount}
                            <span style="white-space-collapse: discard;">
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
                    {/if}
                </span>
                <a class="table-cell link" href="{SITE_PATH}/sale/list?prt={$partner->getId()}"> {$ns.partnersSaleOrdersMappedByPartnerId[$partner->getId()]|@count} </a>
                <a class="table-cell link" href="{SITE_PATH}/purchase/list?prt={$partner->getId()}"> {$ns.partnersPurchaseOrdersMappedByPartnerId[$partner->getId()]|@count} </a>
                <a class="table-cell link" href="{SITE_PATH}/payment/list?prt={$partner->getId()}"> {$ns.partnersTransactionsMappedByPartnerId[$partner->getId()]|@count} </a>
                <a class="table-cell view_item" href="{SITE_PATH}/partner/{$partner->getId()}">
                    <span class="button blue">open</span>
                </a>
            </div>
        {/foreach}
    </div>

    <a href="{SITE_PATH}/partner/create"><img src="{SITE_PATH}/img/add.png"/></a>
</div>