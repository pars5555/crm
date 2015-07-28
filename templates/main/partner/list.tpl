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

    <a class="button" href="{SITE_PATH}/partner/create">create</a>
    {include file="{getTemplateDir}/main/partner/list_filters.tpl"}
    <div>
        <span> ID </span>
        <span> Name</span>
        <span> Email </span>
        <span> Address </span>
        <span> Dept </span>
        <span> Sale Orders</span>
        <span> Purchase Orders</span>
        <span> Payments Transactions </span>
    </div> 
    {foreach from=$ns.partners item=partner}
        <div>
            <a href="{SITE_PATH}/partner/{$partner->getId()}">{$partner->getId()} </a>
            <span> {$partner->getName()} </span>
            <span> {$partner->getEmail()} </span>
            <span> {$partner->getAddress()} </span>
            <span  > 
                {if isset($partnerDept[$partner->getId()])}
                    {foreach from=$partnerDept[$partner->getId()] key=currencyId item=amount}
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
            <a href="{SITE_PATH}/sale/list?prt={$partner->getId()}"> {$ns.partnersSaleOrdersMappedByPartnerId[$partner->getId()]|@count} </a>
            <a href="{SITE_PATH}/purchase/list?prt={$partner->getId()}"> {$ns.partnersPurchaseOrdersMappedByPartnerId[$partner->getId()]|@count} </a>
            <a href="{SITE_PATH}/payment/list?prt={$partner->getId()}"> {$ns.partnersTransactionsMappedByPartnerId[$partner->getId()]|@count} </a>
        </div>
    {/foreach}

</div>