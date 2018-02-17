<div class="container rorder--list--container">
    <h1 class="main_title">Recipient Orders</h1>

    {if isset($ns.error_message)}
        {include file="{ngs cmd=get_template_dir}/main/message.tpl" type="error" content="{$ns.error_message}"} 
    {/if}
    {if isset($ns.success_message)}
        {include file="{ngs cmd=get_template_dir}/main/message.tpl" type="success" content="{$ns.success_message}"} 
    {/if}

    {include file="{ngs cmd=get_template_dir}/main/rorder/list_filters.tpl"}
    <a  href="{$SITE_PATH}/rorder/create"><img src="{$SITE_PATH}/img/new_order.png"/></a>
    <div class="table_striped">
        <div class="table_header_group">
            <span class="table-cell"> ID </span>
            <span class="table-cell"> Recipient </span>
            <span class="table-cell"> Date</span>
            <span class="table-cell"> Note </span>
            <span class="table-cell"> View </span>
            <span class="table-cell"> Edit </span>
            <span class="table-cell"> Delete </span>
        </div> 
        {foreach from=$ns.recipientOrders item=recipientOrder}
            <div class="table-row" {if $recipientOrder->getCancelled() == 1}style="background: red"{/if}>
                <a class="table-cell" href="{$SITE_PATH}/rorder/{$recipientOrder->getId()}">
                    <span>{$recipientOrder->getId()} </span>
                </a>
                <span class="table-cell"> {$recipientOrder->getRecipientDto()->getName()} </span>
                <span class="table-cell"> {$recipientOrder->getOrderDate()} </span>
                <span class="table-cell"> {$recipientOrder->getNote()} </span>
                <a class="table-cell view_item" href="{$SITE_PATH}/rorder/{$recipientOrder->getId()}">
                    <span class="button_icon" title="View">
                        <i class="fa fa-eye"></i>
                    </span>
                </a>
               
                
                <a class="table-cell view_item" href="{$SITE_PATH}/rorder/edit/{$recipientOrder->getId()}">
                    <span class="button_icon" title="Edit">
                        <i class="fa fa-pencil"></i>
                    </span>
                </a>
                {if $recipientOrder->getCancelled() == 1}
                    <a class="table-cell deleteRecipientOrder"  href="{$SITE_PATH}/dyn/main_rorder/do_delete_recipient_order?id={$recipientOrder->getId()}">
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