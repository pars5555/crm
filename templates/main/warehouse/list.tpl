<div class="container sale--list--container">
    <h1 class="main_title">Warehouses</h1>

    <div class="main-table">
        <table>
            <tr>
                <th>Id</th>
                <th>Partner</th>
                <th>Date</th>
                <th>Billing Deadline</th>
                <th>Total Amount</th>
                {if $ns.showprofit == 1}
                    <th> Total Profit </th>
                {/if}
                <th>Note</th>
                <th class="icon-cell">Warranty</th>
                <th class="icon-cell">View</th>
                <th class="icon-cell">Edit</th>
                <th class="icon-cell">Delete</th>
            </tr>
            {foreach from=$ns.saleOrders item=saleOrder}
                <tr>
                    <td class="link-cell id">
                        <a href="{$SITE_PATH}/sale/{$saleOrder->getId()}">
                            <span>{$saleOrder->getId()} </span>
                        </a>
                    </td>
                    <td>{$saleOrder->getPartnerDto()->getName()}</td>
                    <td>{$saleOrder->getOrderDate()}</td>
                    <td style="{if $smarty.now|date_format:"%Y-%m-%d">=$saleOrder->getBillingDeadline() && $saleOrder->getBilled()==0}color:red{/if}">{$saleOrder->getBillingDeadline()}</td>
                    {assign totalAmount $saleOrder->getTotalAmount()}
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
                    {if $ns.showprofit == 1}
                        <td>{$saleOrder->getTotalProfit()}</td>
                    {/if}
                    <td>{$saleOrder->getNote()}</td>
                    <td class="icon-cell">
                        <a href="{$SITE_PATH}/sale/warranty/{$saleOrder->getId()}">
                            <span class="button_icon" title="Warranty">
                                <i class="fa fa-file-text-o"></i>
                            </span>
                        </a>
                    </td>
                    <td class="icon-cell">
                        <a href="{$SITE_PATH}/sale/{$saleOrder->getId()}">
                            <span class="button_icon" title="View">
                                <i class="fa fa-eye"></i>
                            </span>
                        </a>
                    </td>
                    <td class="icon-cell">
                        <a href="{$SITE_PATH}/sale/edit/{$saleOrder->getId()}">
                            <span class="button_icon" title="Edit">
                                <i class="fa fa-pencil"></i>
                            </span>
                        </a>
                    </td>
                    {if $saleOrder->getCancelled() == 1}
                    <td class="icon-cell">
                        <a class="deleteBilling" href="{$SITE_PATH}/dyn/main_sale/do_delete_sale_order?id={$saleOrder->getId()}">
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