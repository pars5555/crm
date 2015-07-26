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
            <span> {$ns.partnersSaleOrdersMappedByPartnerId[$partner->getId()]|@count} </span>
            <span> {$ns.partnersPurchaseOrdersMappedByPartnerId[$partner->getId()]|@count} </span>
            <span> {$ns.partnersTransactionsMappedByPartnerId[$partner->getId()]|@count} </span>
        </div>
    {/foreach}

</div>