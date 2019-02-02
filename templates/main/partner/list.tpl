<div class="container partner--list--container">
    <h1 class="main_title">Partners</h1>

    {if isset($ns.error_message)}
        {include file="{ngs cmd=get_template_dir}/main/message.tpl" type="error" content="{$ns.error_message}"}
    {/if}
    {if isset($ns.success_message)}
        {include file="{ngs cmd=get_template_dir}/main/message.tpl" type="success" content="{$ns.success_message}"}
    {/if}

    {include file="{ngs cmd=get_template_dir}/main/partner/list_filters.tpl"}
    <div class="main-table">
        <table>
            <tr>
                <th>Id</th>
                <th>Incl. in Capital</th>
                <th>Name</th>
                <th>Email</th>
                <th>Tel.</th>
                <th>Debt</th>
                <th>Sale Orders</th>
                <th>Purchase Orders</th>
                <th>Payments Transactions</th>
                <th>Billing Transactions</th>
                <th class="icon-cell">All Deals</th>
                <th class="icon-cell">View</th>
                <th class="icon-cell">Edit</th>
                <th class="icon-cell">Hidden</th>
            </tr>
            {foreach from=$ns.partners item=partner}
                {if  (isset($ns.partnersZeroDebt[$partner->getId()]) && $ns.partnersZeroDebt[$partner->getId()] == 0) || $ns.selectedFilterHidden == 'all'}
                    <tr>
                        <td class="link-cell id">
                            {if isset($ns.attachments[$partner->getId()])}
                                <img src="{$SITE_PATH}/img/attachment.png" width="32"/>
                            {/if}
                            <a class="table-cell" href="{$SITE_PATH}/partner/{$partner->getId()}">
                                <span>{$partner->getId()} </span>
                            </a>
                        </td>
                        <td class="hide-check icon-cell">
                            <input class="f_included_in_capital_checkbox"
                                   data-partner_id="{$partner->getId()}"
                                   type="checkbox" value="1" {if $partner->getIncludedInCapital() ==1}checked{/if}/>
                        </td>
                        <td class="name">{$partner->getName()}</td>
                        <td class="email">{$partner->getEmail()}</td>
                        <td class="phone">{$partner->getPhone()|replace:',':'</br>'}</td>
                        <td class="debt">
                            {if isset($ns.partnersDebt[$partner->getId()])}
                                {foreach from=$ns.partnersDebt[$partner->getId()] key=currencyId item=amount}
                                    {assign currencyDto $ns.currencies[$currencyId]}
                                    {if $currencyDto->getSymbolPosition() == 'left'}
                                        {$currencyDto->getTemplateChar()}
                                    {/if}
                                    {$amount}
                                    {if $currencyDto->getSymbolPosition() == 'right'}
                                        {$currencyDto->getTemplateChar()}
                                    {/if}
                                {/foreach}
                            {/if}
                        </td>
                        <td class="link-cell sale-orders">
                            <a class="link" href="{$SITE_PATH}/sale/list?prt={$partner->getId()}">
                                {$ns.partnersSaleOrdersMappedByPartnerId[$partner->getId()]|@count}
                            </a>
                        </td>
                        <td class="link-cell purchase-orders">
                            <a class="link" href="{$SITE_PATH}/purchase/list?prt={$partner->getId()}">
                                {$ns.partnersPurchaseOrdersMappedByPartnerId[$partner->getId()]|@count}
                            </a>
                        </td>
                        <td class="link-cell payment-transactions">
                            <a class="link" href="{$SITE_PATH}/payment/list?prt={$partner->getId()}">
                                {$ns.partnersPaymentTransactionsMappedByPartnerId[$partner->getId()]|@count}
                            </a>
                        </td>
                        <td class="link-cell billing-transactions">
                            <a class="link" href="{$SITE_PATH}/billing/list?prt={$partner->getId()}">
                                {$ns.partnersBillingTransactionsMappedByPartnerId[$partner->getId()]|@count}
                            </a>
                        </td>
                        <td class="link-cell all-deals icon-cell">
                            <a class="view_item" href="{$SITE_PATH}/partner/all/{$partner->getId()}">
                                <span class="button_icon" title="View">
                                    <i class="fa fa-calendar"></i>
                                </span>
                            </a>
                        </td>
                        <td class="link-cell view icon-cell">
                            <a class="view_item" href="{$SITE_PATH}/partner/{$partner->getId()}">
                                <span class="button_icon" title="View">
                                    <i class="fa fa-eye"></i>
                                </span>
                            </a>
                        </td>
                        <td class="link-cell edit icon-cell">
                            <a class="view_item" href="{$SITE_PATH}/partner/edit/{$partner->getId()}">
                                <span class="button_icon" title="Edit">
                                    <i class="fa fa-pencil"></i>
                                </span>
                            </a>
                        </td>
                        <td class="hide-check icon-cell">
                            <input class="f_hidden_checkbox"
                                   data-partner_id="{$partner->getId()}"
                                   type="checkbox" value="1" {if $partner->getHidden() ==1}checked{/if}/>
                        </td>
                    </tr>
                {/if}
            {/foreach}
        </table>
    </div>
</div>