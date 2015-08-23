<div class="container billing--list--container">
    <h1>Billing Orders</h1>
    
    {if isset($ns.error_message)}
        {include file="{getTemplateDir}/main/message.tpl" type="error" content="{$ns.error_message}"} 
    {/if}
    {if isset($ns.success_message)}
        {include file="{getTemplateDir}/main/message.tpl" type="success" content="{$ns.success_message}"} 
    {/if}

    {include file="{getTemplateDir}/main/billing/list_filters.tpl"}
    <a  href="{SITE_PATH}/billing/create"><img src="{SITE_PATH}/img/add.png"/></a>
    <div class="table_striped">
        <div class="table_header_group">
            <span class="table-cell"> ID </span>
            <span class="table-cell"> DATE </span>
            <span class="table-cell"> Partner </span>
            <span class="table-cell"> Billing Method </span>
            <span class="table-cell"> Amount </span>
            <span class="table-cell"> Note </span>
            <span class="table-cell"> View </span>
            <span class="table-cell"> Edit </span>
            <span class="table-cell"> Delete </span>
        </div> 
        {foreach from=$ns.billings item=billing}
            <div class="table-row" {if $billing->getCancelled() == 1}style="background: red"{/if} href="{SITE_PATH}/billing/{$billing->getId()}">
                <a class="table-cell" href="{SITE_PATH}/billing/{$billing->getId()}">
                    <span>{$billing->getId()} </span>
                </a>
                <span class="table-cell"> {$billing->getDate()} </span>
                <span class="table-cell"> {$billing->getPartnerDto()->getName()} </span>
                <span class="table-cell"> {$billing->getPaymentMethodDto()->getName()} </span>
                <span class="table-cell"> 
                    {if $billing->getCurrencyDto()->getSymbolPosition() == 'left'}
                        {$billing->getCurrencyDto()->getTemplateChar()}
                    {/if}
                    {-$billing->getAmount()}
                    {if $billing->getCurrencyDto()->getSymbolPosition() == 'right'}
                        {$billing->getCurrencyDto()->getTemplateChar()}
                    {/if}
                </span>
                <span class="table-cell"> {$billing->getNote()} </span>
                <a class="table-cell" href="{SITE_PATH}/billing/{$billing->getId()}">
                    <span class="button_icon" title="View">
                        <i class="fa fa-eye"></i>
                    </span>
                </a>
                <a class="table-cell" href="{SITE_PATH}/billing/edit/{$billing->getId()}">
                    <span class="button_icon" title="Edit">
                        <i class="fa fa-pencil"></i>
                    </span>
                </a>
                {if $billing->getCancelled() == 1}
                    <a class="table-cell deleteBilling"  href="{SITE_PATH}/dyn/main_billing/do_delete_billing?id={$billing->getId()}">
                        <span class="button_icon" title="delete">
                            <i class="fa fa-trash-o"></i>
                        </span>
                    </a>
                {else}
                    <a class="table-cell" href="javascript:void(0);">
                    </a>
                {/if}
            </div>
        {/foreach}


    </div>



</div>