<div class="container sale--list--container">
    <h1 class="main_title">Sale Orders</h1>

    {if isset($ns.error_message)}
        {include file="{ngs cmd=get_template_dir}/main/message.tpl" type="error" content="{$ns.error_message}"} 
    {/if}
    {if isset($ns.success_message)}
        {include file="{ngs cmd=get_template_dir}/main/message.tpl" type="success" content="{$ns.success_message}"} 
    {/if}

    {include file="{ngs cmd=get_template_dir}/main/sale/list_filters.tpl"}
    <a href="{$SITE_PATH}/sale/create"><img src="{$SITE_PATH}/img/new_order.png"/></a>
    <div class="table_striped">
        <div class="table_header_group">
            <span class="table-cell"> ID </span>
            <span class="table-cell"> Partner </span>
            <span class="table-cell"> Date</span>
            <span class="table-cell"> Billing Deadline </span>
            <span class="table-cell"> Total Amount </span>
            {if $ns.showprofit == 1}
                <span class="table-cell"> Total Profit </span>
            {/if}
            <span class="table-cell"> Note </span>
            <span class="table-cell"> Warranty </span>
            <span class="table-cell"> View </span>
            <span class="table-cell"> Edit </span>
            <span class="table-cell"> Delete </span>
        </div> 
        {foreach from=$ns.saleOrders item=saleOrder}
            <div class="table-row" {if $saleOrder->getCancelled() == 1}style="background: red"{/if}>
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
                {if $ns.showprofit == 1}
                    <span class="table-cell"> {$saleOrder->getTotalProfit()} </span>
                {/if}
                <span class="table-cell"> {$saleOrder->getNote()} </span>
                
                <a class="table-cell view_item" href="{$SITE_PATH}/sale/warranty/{$saleOrder->getId()}">
                    <span class="button_icon" title="Warranty">
                        <i class="fa fa-file-text-o"></i>
                    </span>
                </a>
                
                <a class="table-cell view_item" href="{$SITE_PATH}/sale/{$saleOrder->getId()}">
                    <span class="button_icon" title="View">
                        <i class="fa fa-eye"></i>
                    </span>
                </a>
                <a class="table-cell view_item" href="{$SITE_PATH}/sale/edit/{$saleOrder->getId()}">
                    <span class="button_icon" title="Edit">
                        <i class="fa fa-pencil"></i>
                    </span>
                </a>
                {if $saleOrder->getCancelled() == 1}
                    <a class="table-cell view_item deleteSaleOrder"  href="{$SITE_PATH}/dyn/main_sale/do_delete_sale_order?id={$saleOrder->getId()}">
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