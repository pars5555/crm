<div class="container purchase--list--container">
    <h1 class="main_title">Purchase Orders</h1>

    {if isset($ns.error_message)}
        {include file="{ngs cmd=get_template_dir}/main/message.tpl" type="error" content="{$ns.error_message}"} 
    {/if}
    {if isset($ns.success_message)}
        {include file="{ngs cmd=get_template_dir}/main/message.tpl" type="success" content="{$ns.success_message}"} 
    {/if}

    {include file="{ngs cmd=get_template_dir}/main/purchase/list_filters.tpl"}
    <a  href="{$SITE_PATH}/purchase/create"><img src="{$SITE_PATH}/img/new_order.png"/></a>
    <div class="table_striped">
        <div class="table_header_group">
            <span class="table-cell"> ID </span>
            <span class="table-cell"> Partner </span>
            <span class="table-cell"> Date</span>
            <span class="table-cell"> Payment Deadline </span>
            <span class="table-cell"> Total Amount</span>
            <span class="table-cell"> Note </span>
            <span class="table-cell"> Warranty </span>
            <span class="table-cell"> View </span>
            <span class="table-cell"> Edit </span>
            <span class="table-cell"> Delete </span>
        </div> 
        {foreach from=$ns.purchaseOrders item=purchaseOrder}
            <div class="table-row" {if $purchaseOrder->getCancelled() == 1}style="color: gray"{/if}>
                <a class="table-cell" href="{$SITE_PATH}/purchase/{$purchaseOrder->getId()}">
                    <span>{$purchaseOrder->getId()} </span>
                </a>
                <span class="table-cell"> {$purchaseOrder->getPartnerDto()->getName()} </span>
                <span class="table-cell"> {$purchaseOrder->getOrderDate()} </span>
                <span class="table-cell" style="{if $smarty.now|date_format:"%Y-%m-%d">=$purchaseOrder->getPaymentDeadline()  && $purchaseOrder->getPaid()==0}color:red{/if}"> {$purchaseOrder->getPaymentDeadline()} </span>
                {assign totalAmount $purchaseOrder->getTotalAmount()}
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
                <span class="table-cell"> {$purchaseOrder->getNote()} </span>
                
                 <a class="table-cell view_item" href="{$SITE_PATH}/purchase/warranty/{$purchaseOrder->getId()}">
                    <span class="button_icon" title="Warranty">
                        <i class="fa fa-file-text-o"></i>
                    </span>
                </a>
                
                <a class="table-cell view_item" href="{$SITE_PATH}/purchase/{$purchaseOrder->getId()}">
                    <span class="button_icon" title="View">
                        <i class="fa fa-eye"></i>
                    </span>
                </a>
               
                
                <a class="table-cell view_item" href="{$SITE_PATH}/purchase/edit/{$purchaseOrder->getId()}">
                    <span class="button_icon" title="Edit">
                        <i class="fa fa-pencil"></i>
                    </span>
                </a>
                {if $purchaseOrder->getCancelled() == 1}
                    <a class="table-cell deletePurchaseOrder"  href="{$SITE_PATH}/dyn/main_purchase/do_delete_purchase_order?id={$purchaseOrder->getId()}">
                        <span class="button_icon" title="delete">
                            <i class="fa fa-trash-o"></i>
                        </span>
                    </a>
                {else}
                    <span class="table-cell" href="javascript:void(0);">
                    </span>
                {/if}


                
            </div>
        {/foreach}
    </div>

</div>