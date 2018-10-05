<div class="container purchase--list--container">
    <h1 class="main_title">Purchase Orders</h1>

    {if isset($ns.error_message)}
        {include file="{ngs cmd=get_template_dir}/main/message.tpl" type="error" content="{$ns.error_message}"}
    {/if}
    {if isset($ns.success_message)}
        {include file="{ngs cmd=get_template_dir}/main/message.tpl" type="success" content="{$ns.success_message}"}
    {/if}

    {include file="{ngs cmd=get_template_dir}/main/purchase/list_filters.tpl"}
    <div class="main-table">
        <table>
            <tr>
                <th>Id</th>
                <th>Partner</th>
                <th>Date</th>
                <th>Payment Deadline</th>
                <th>Total Amount</th>
                <th>Note</th>
                <th class="icon-cell">Warranty</th>
                <th class="icon-cell">View</th>
                <th class="icon-cell">Edit</th>
                <th class="icon-cell">Delete</th>
            </tr>
            {foreach from=$ns.purchaseOrders item=purchaseOrder}
                <tr {if $purchaseOrder->getCancelled() == 1}style="color: gray"{/if}>
                    <td class="link-cell id">
                        <a href="{$SITE_PATH}/purchase/{$purchaseOrder->getId()}">
                            <span>{$purchaseOrder->getId()} </span>
                        </a>
                    </td>
                    <td>{$purchaseOrder->getPartnerDto()->getName()}</td>
                    <td>{$purchaseOrder->getOrderDate()}</td>
                    <td style="{if $smarty.now|date_format:"%Y-%m-%d">=$purchaseOrder->getPaymentDeadline() && $purchaseOrder->getPaid()==0}color:red{/if}">{$purchaseOrder->getPaymentDeadline()}</td>
                    {assign totalAmount $purchaseOrder->getTotalAmount()}
                    <td>
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
                    </td>
                    <td>{$purchaseOrder->getNote()}</td>
                    <td class="icon-cell">
                        <a href="{$SITE_PATH}/purchase/warranty/{$purchaseOrder->getId()}">
                            <span class="button_icon" title="Warranty">
                                <i class="fa fa-file-text-o"></i>
                            </span>
                        </a>
                    </td>
                    <td class="icon-cell">
                        <a href="{$SITE_PATH}/purchase/{$purchaseOrder->getId()}">
                            <span class="button_icon" title="View">
                                <i class="fa fa-eye"></i>
                            </span>
                        </a>
                    </td>
                    <td class="icon-cell">
                        <a href="{$SITE_PATH}/purchase/edit/{$purchaseOrder->getId()}">
                            <span class="button_icon" title="Edit">
                                <i class="fa fa-pencil"></i>
                            </span>
                        </a>
                    </td>
                    {if $purchaseOrder->getCancelled() == 1}
                        <td class="icon-cell">
                            <a class="deleteBilling" href="{$SITE_PATH}/dyn/main_purchase/do_delete_purchase_order?id={$purchaseOrder->getId()}">
                                <span class="button_icon" title="delete">
                                    <i class="fa fa-trash-o"></i>
                                </span>
                            </a>
                        </td>
                    {else}
                        <td></td>
                    {/if}
                </tr>
            {/foreach}
        </table>
    </div>
</div>