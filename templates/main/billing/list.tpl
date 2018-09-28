<div class="container billing--list--container">
    <h1 class="main_title">Billing Orders</h1>

    {if isset($ns.error_message)}
        {include file="{ngs cmd=get_template_dir}/main/message.tpl" type="error" content="{$ns.error_message}"}
    {/if}
    {if isset($ns.success_message)}
        {include file="{ngs cmd=get_template_dir}/main/message.tpl" type="success" content="{$ns.success_message}"}
    {/if}

    {include file="{ngs cmd=get_template_dir}/main/billing/list_filters.tpl"}
    <a  href="{$SITE_PATH}/billing/create"><img src="{$SITE_PATH}/img/add.png"/></a>
    <div class="main-table">
        <table>
            <tr>
                <th>Id</th>
                <th>Partner</th>
                <th>Date</th>
                <th>Billing Method</th>
                <th>Amount</th>
                <th>Note</th>
                <th class="icon-cell">View</th>
                <th class="icon-cell">Edit</th>
                <th class="icon-cell">Delete</th>
            </tr>
            {foreach from=$ns.billings item=billing}
            <tr {if $billing->getCancelled() == 1}style="background: red"{/if}>
                <td class="link-cell id">
                    <a href="{$SITE_PATH}/billing/{$billing->getId()}">
                        <span>{$billing->getId()} </span>
                    </a>
                </td>
                <td>{$billing->getPartnerDto()->getName()}</td>
                <td>{$billing->getDate()}</td>
                <td>{$billing->getPaymentMethodDto()->getName()}</td>
                <td>
                    {if $billing->getCurrencyDto()->getSymbolPosition() == 'left'}
                        {$billing->getCurrencyDto()->getTemplateChar()}
                    {/if}
                    {-$billing->getAmount()}
                    {if $billing->getCurrencyDto()->getSymbolPosition() == 'right'}
                        {$billing->getCurrencyDto()->getTemplateChar()}
                    {/if}
                </td>
                <td>{$billing->getNote()}</td>
                <td class="icon-cell">
                    <a href="{$SITE_PATH}/billing/{$billing->getId()}">
                        <span class="button_icon" title="View">
                            <i class="fa fa-eye"></i>
                        </span>
                    </a>
                </td>
                <td class="icon-cell">
                    <a href="{$SITE_PATH}/billing/edit/{$billing->getId()}">
                        <span class="button_icon" title="Edit">
                            <i class="fa fa-pencil"></i>
                        </span>
                    </a>
                </td>
                {if $billing->getCancelled() == 1}
                <td class="icon-cell">
                    <a class="deleteBilling" href="{$SITE_PATH}/dyn/main_billing/do_delete_billing?id={$billing->getId()}">
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