<div class="container payment--list--container">
    <h1 class="main_title">Payment Orders</h1>

    {if isset($ns.error_message)}
        {include file="{ngs cmd=get_template_dir}/main/message.tpl" type="error" content="{$ns.error_message}"}
    {/if}
    {if isset($ns.success_message)}
        {include file="{ngs cmd=get_template_dir}/main/message.tpl" type="success" content="{$ns.success_message}"}
    {/if}

    {include file="{ngs cmd=get_template_dir}/main/payment/list_filters.tpl"}

    <div class="main-table">
        <table>
            <tr>
                <th>Id</th>
                <th>Partner</th>
                <th>Date</th>
                <th>Payment Method</th>
                <th>Amount</th>
                <th>Note</th>
                <th class="icon-cell">Expense</th>
                <th class="icon-cell">Paid</th>
                <th class="icon-cell">View</th>
                <th class="icon-cell">Edit</th>
                <th class="icon-cell">Delete</th>
            </tr>
            {foreach from=$ns.payments item=payment}
                <tr {if $payment->getCancelled() == 1}style="color: gray"{/if}>
                    <td class="link-cell id">
                        <a href="{$SITE_PATH}/payment/{$payment->getId()}">
                            <span>{$payment->getId()} </span>
                        </a>
                    </td>
                    <td>{$payment->getPartnerDto()->getName()}</td>
                    <td>{$payment->getDate()}</td>
                    <td>{$payment->getPaymentMethodDto()->getName()}</td>
                    <td>
                        {if $payment->getCurrencyDto()->getSymbolPosition() == 'left'}
                            {$payment->getCurrencyDto()->getTemplateChar()}
                        {/if}
                        {$payment->getAmount()}
                        {if $payment->getCurrencyDto()->getSymbolPosition() == 'right'}
                            {$payment->getCurrencyDto()->getTemplateChar()}
                        {/if}
                    </td>
                    <td>{$payment->getNote()}</td>
                    <td class="icon-cell">
                        {if $payment->getIsExpense() == 1}
                            <i class="fa fa-exclamation-triangle"></i>
                        {else}
                        {/if}
                    </td>
                    <td class="icon-cell">
                        {if $payment->getPaid() == 1}
                            <i class="fa fa-check"></i>
                        {else}
                            <i class="fa fa-times"></i>
                        {/if}
                    </td>
                    <td class="icon-cell">
                        <a href="{$SITE_PATH}/payment/{$payment->getId()}">
                            <span class="button_icon" title="View">
                                <i class="fa fa-eye"></i>
                            </span>
                        </a>
                    </td>
                    <td class="icon-cell">
                        <a href="{$SITE_PATH}/payment/edit/{$payment->getId()}">
                            <span class="button_icon" title="Edit">
                                <i class="fa fa-pencil"></i>
                            </span>
                        </a>
                    </td>
                    {if $payment->getCancelled() == 1}
                        <td class="icon-cell">
                            <a class="deleteBilling" href="{$SITE_PATH}/dyn/main_payment/do_delete_payment?id={$payment->getId()}">
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