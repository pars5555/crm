<div class="container partner--list--container">
    <h1 class="main_title">Partners</h1>
    
    {if isset($ns.error_message)}
        {include file="{ngs cmd=get_template_dir}/main/message.tpl" type="error" content="{$ns.error_message}"} 
    {/if}
    {if isset($ns.success_message)}
        {include file="{ngs cmd=get_template_dir}/main/message.tpl" type="success" content="{$ns.success_message}"} 
    {/if}

    {include file="{ngs cmd=get_template_dir}/main/partner/list_filters.tpl"}
<a href="{$SITE_PATH}/partner/create"><img src="{$SITE_PATH}/img/add.png"/></a>
    <div class="table_striped">
        <div class="table_header_group">
            <span class="table-cell"> ID </span>
            <span class="table-cell"> Name</span>
            <span class="table-cell"> Email </span>
            <span class="table-cell"> Tel. </span>
            <span class="table-cell"> Dept </span>
            <span class="table-cell"> Sale Orders</span>
            <span class="table-cell"> Purchase Orders</span>
            <span class="table-cell"> Payments Transactions </span>
            <span class="table-cell"> Billing Transactions </span>
            <span class="table-cell"> All Deals </span>
            <span class="table-cell"> View </span>
            <span class="table-cell"> Edit </span>
        </div> 
        {foreach from=$ns.partners item=partner}
            <div class="table-row">
                <a class="table-cell" href="{$SITE_PATH}/partner/{$partner->getId()}">
                    <span>{$partner->getId()} </span>
                </a>
                <span class="table-cell">{$partner->getName()} </span>
                <span class="table-cell"> {$partner->getEmail()} </span>
                <span class="table-cell " style="white-space: nowrap"> {$partner->getPhone()|replace:',':'</br>'} </span>
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
                <a class="table-cell link" href="{$SITE_PATH}/sale/list?prt={$partner->getId()}"> {$ns.partnersSaleOrdersMappedByPartnerId[$partner->getId()]|@count} </a>
                <a class="table-cell link" href="{$SITE_PATH}/purchase/list?prt={$partner->getId()}"> {$ns.partnersPurchaseOrdersMappedByPartnerId[$partner->getId()]|@count} </a>
                <a class="table-cell link" href="{$SITE_PATH}/payment/list?prt={$partner->getId()}"> {$ns.partnersPaymentTransactionsMappedByPartnerId[$partner->getId()]|@count} </a>
                <a class="table-cell link" href="{$SITE_PATH}/billing/list?prt={$partner->getId()}"> {$ns.partnersBillingTransactionsMappedByPartnerId[$partner->getId()]|@count} </a>
                <a class="table-cell view_item" href="{$SITE_PATH}/partner/all/{$partner->getId()}">
                    <span class="button_icon" title="View">
                        <i class="fa fa-calendar"></i>
                    </span>
                </a>
                <a class="table-cell view_item" href="{$SITE_PATH}/partner/{$partner->getId()}">
                    <span class="button_icon" title="View">
                        <i class="fa fa-eye"></i>
                    </span>
                </a>
                <a class="table-cell view_item" href="{$SITE_PATH}/partner/edit/{$partner->getId()}">
                    <span class="button_icon" title="Edit">
                        <i class="fa fa-pencil"></i>
                    </span>
                </a>
            </div>
        {/foreach}
    </div>

    
</div>