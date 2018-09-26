<div class="container partner--list--container">
    <h1 class="main_title">Partners</h1>

    {if isset($ns.error_message)}
        {include file="{ngs cmd=get_template_dir}/main/message.tpl" type="error" content="{$ns.error_message}"} 
    {/if}
    {if isset($ns.success_message)}
        {include file="{ngs cmd=get_template_dir}/main/message.tpl" type="success" content="{$ns.success_message}"} 
    {/if}

    {include file="{ngs cmd=get_template_dir}/main/partner/list_filters.tpl"}

    <div class="table main-table">
        <table style="border-collapse: collapse;  width: 100%;border: 1px solid black">
            <tr>
                <th>Id</th><th>Name</th><th>Email</th><th>Tel.</th><th>Debt</th><th>SaleOrders</th><th>PurchaseOrders</th><th>Payments Transactions</th><th>Billing Transactions</th><th>All Deals</th><th>View</th><th>Edit</th><th>Hidden</th></tr>
                    {foreach from=$ns.partners item=partner}
                <tr>
                    <td><a class="table-cell" href="{$SITE_PATH}/partner/{$partner->getId()}">
                            <span>{$partner->getId()} </span>
                        </a></td>
                    <td>{$partner->getName()}</td>
                    <td>{$partner->getEmail()}</td>
                    <td style="white-space: nowrap">{$partner->getPhone()|replace:',':'</br>'}</td>
                    <td>{if isset($partnersDebt[$partner->getId()])}
                        {foreach from=$partnersDebt[$partner->getId()] key=currencyId item=amount}
                            <span style="white-space-collapse: discard;">
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
                        {/if}
                        </td>
                        <td><a class="table-cell link" href="{$SITE_PATH}/sale/list?prt={$partner->getId()}"> {$ns.partnersSaleOrdersMappedByPartnerId[$partner->getId()]|@count} </a></td>
                        <td><a class="table-cell link" href="{$SITE_PATH}/purchase/list?prt={$partner->getId()}"> {$ns.partnersPurchaseOrdersMappedByPartnerId[$partner->getId()]|@count} </a></td>
                        <td><a class="table-cell link" href="{$SITE_PATH}/payment/list?prt={$partner->getId()}"> {$ns.partnersPaymentTransactionsMappedByPartnerId[$partner->getId()]|@count} </a></td>
                        <td><a class="table-cell link" href="{$SITE_PATH}/billing/list?prt={$partner->getId()}"> {$ns.partnersBillingTransactionsMappedByPartnerId[$partner->getId()]|@count} </a></td>
                        <td><a class="table-cell view_item" href="{$SITE_PATH}/partner/all/{$partner->getId()}">
                                <span class="button_icon" title="View">
                                    <i class="fa fa-calendar"></i>
                                </span>
                            </a>
                        </td>
                        <td><a class="table-cell view_item" href="{$SITE_PATH}/partner/{$partner->getId()}">
                                <span class="button_icon" title="View">
                                    <i class="fa fa-eye"></i>
                                </span>
                            </a>
                        </td>
                        <td><a class="table-cell view_item" href="{$SITE_PATH}/partner/edit/{$partner->getId()}">
                                <span class="button_icon" title="Edit">
                                    <i class="fa fa-pencil"></i>
                                </span>
                            </a>
                        </td>
                        <td><input class="f_hidden_checkbox" data-partner_id="{$partner->getId()}" type="checkbox" value="1" {if $partner->getHidden() ==1}checked{/if}/></td>

                    </tr>
                    {/foreach}
                    </table>


                </div>
            </div>