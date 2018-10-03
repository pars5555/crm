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

    <div class="main-table">
        <table>
            <tr>
                <th>Id</th>
                <th>Recipient</th>
                <th>Date</th>
                <th>Note</th>
                <th class="icon-cell">View</th>
                <th class="icon-cell">Edit</th>
                <th class="icon-cell">Delete</th>
            </tr>

            {foreach from=$ns.recipientOrders item=recipientOrder}
                <tr {if $recipientOrder->getCancelled() == 1}style="color: gray"{/if}>
                    <td class="link-cell id">
                        <a href="{$SITE_PATH}/rorder/{$recipientOrder->getId()}">
                            <span>{$recipientOrder->getId()} </span>
                        </a>
                    </td>
                    <td>{$recipientOrder->getRecipientDto()->getName()}</td>
                    <td>{$recipientOrder->getOrderDate()}</td>
                    <td>{$recipientOrder->getNote()}</td>
                    <td class="icon-cell">
                        <a class="view_item" href="{$SITE_PATH}/rorder/{$recipientOrder->getId()}">
                            <span class="button_icon" title="View">
                                <i class="fa fa-eye"></i>
                            </span>
                        </a>
                    </td>
                    <td class="icon-cell">
                        <a class="view_item" href="{$SITE_PATH}/rorder/edit/{$recipientOrder->getId()}">
                            <span class="button_icon" title="Edit">
                                <i class="fa fa-pencil"></i>
                            </span>
                        </a>
                    </td>
                    <td class="icon-cell">
                        {if $recipientOrder->getCancelled() == 1}
                            <a class="deleteRecipientOrder"  href="{$SITE_PATH}/dyn/main_rorder/do_delete_recipient_order?id={$recipientOrder->getId()}">
                                <span class="button_icon" title="delete">
                                    <i class="fa fa-trash-o"></i>
                                </span>
                            </a>
                        {else}
                        {/if}
                    </td>
                </tr>
            {/foreach}
        </table>
    </div>
</div>