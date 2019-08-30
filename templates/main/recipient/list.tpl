<div class="container partner--list--container">
    <h1 class="main_title">Recipients</h1>

    {if isset($ns.error_message)}
        {include file="{ngs cmd=get_template_dir}/main/message.tpl" type="error" content="{$ns.error_message}"} 
    {/if}
    {if isset($ns.success_message)}
        {include file="{ngs cmd=get_template_dir}/main/message.tpl" type="success" content="{$ns.success_message}"} 
    {/if}

    {include file="{ngs cmd=get_template_dir}/main/recipient/list_filters.tpl"}

    <div class="main-table">
        <table>
            <tr>
                <th>Id</th>
                <th>First Name</th>
                <th>Last Name</th>
                    {if $ns.user->getType() == 'root'}
                    <th>Email</th>
                    {/if}
                    {*                <th>Tel.</th>*}
                     {if $ns.selectedFilterShowStandardUnits == 'yes' || $ns.user->getType() == 'level2'}
                    <th>Ex Unit Glob</th>
                    {/if}
                <th>Ex Unit Glob</th>
                    {if $ns.selectedFilterShowStandardUnits == 'yes' || $ns.user->getType() == 'level2'}
                    <th>Ex Unit Onex</th>
                    {/if}
                <th>Ex Unit Onex</th>
                    {if $ns.selectedFilterShowStandardUnits == 'yes' || $ns.user->getType() == 'level2'}
                    <th>Ex Unit Nova</th>
                    {/if}
                <th>Ex Unit Nova</th>
                   {if $ns.selectedFilterShowStandardUnits == 'yes' || $ns.user->getType() == 'level2'}
                    <th>Ex Unit Shipex</th>
                    {/if}
                <th>Ex Unit Shipex</th>
                    {if $ns.selectedFilterShowStandardUnits == 'yes' || $ns.user->getType() == 'level2'}
                    <th>Ex Unit Cheapex</th>
                    {/if}
                <th>Ex Unit Cheapex</th>
                    {if $ns.selectedFilterShowStandardUnits == 'yes'}
                    <th>St Unit</th>
                    {/if}
                    {if $ns.user->getType() == 'root' }
                    <th>Doc Number</th>
                        {*                <th>Doc Type</th>*}
                    <th>Social Id</th>
                    {/if}
                <th>Note</th>
                <th>Favorite</th>
                <th>Orders</th>
                    {if $ns.user->getType() == 'root' }
                    <th class="icon-cell">View</th>
                    <th class="icon-cell">Edit</th>
                    <th class="icon-cell">Checked</th>
                    <th class="icon-cell">Deleted</th>
                    {/if}
            </tr>

            {foreach from=$ns.recipients item=recipient}
                <tr class="table-row"  style="{if $recipient->getDeleted() ==1}background:lightgrey{elseif $recipient->getChecked() == 1}background:lightgreen{/if}"  data-type="recipient" data-id="{$recipient->getId()}">
                    <td class="link-cell id">
                        {if isset($ns.attachments[$recipient->getId()])}
                            <img src="{$SITE_PATH}/img/attachment.png" width="32"/>
                        {/if}
                        <a href="{$SITE_PATH}/recipient/{$recipient->getId()}">
                            <span>{$recipient->getId()} </span>
                        </a>
                    </td>
                    <td>{$recipient->getFirstName()}</td>
                    <td>{$recipient->getLastName()}</td>
                    {if $ns.user->getType() == 'root' }
                        <td class="table-cell f_editable_cell" data-field-name="email">{$recipient->getEmail()}</td>
                    {/if}
                    {*                    <td style="white-space: nowrap"> {$recipient->getPhoneNumber()|replace:',':'</br>'} </td>*}
                    {if $ns.user->getType() == 'root'}
                        <td class="table-cell f_editable_cell" data-field-name="express_unit_address">{$recipient->getExpressUnitAddress()}</td>
                    {/if}
                    {if $ns.selectedFilterShowStandardUnits == 'yes' || $ns.user->getType() == 'level2'}
                    <td class="table-cell f_editable_cell" data-field-name="express_unit_address_1">{$recipient->getExpressUnitAddress_1()}</td>
                    {/if}
                    {if $ns.user->getType() == 'root'}
                        <td class="table-cell f_editable_cell" data-field-name="onex_express_unit">{$recipient->getOnexExpressUnit()}</td>
                    {/if}
                    {if $ns.selectedFilterShowStandardUnits == 'yes' || $ns.user->getType() == 'level2'}
                    <td class="table-cell f_editable_cell" data-field-name="onex_express_unit_1">{$recipient->getOnexExpressUnit_1()}</td>
                    {/if}
                    {if $ns.user->getType() == 'root'}
                        <td class="table-cell f_editable_cell" data-field-name="nova_express_unit">{$recipient->getNovaExpressUnit()}</td>
                    {/if}
                    {if $ns.selectedFilterShowStandardUnits == 'yes' || $ns.user->getType() == 'level2'}
                    <td class="table-cell f_editable_cell" data-field-name="nova_express_unit_1">{$recipient->getNovaExpressUnit_1()}</td>
                    {/if}
                    {if $ns.user->getType() == 'root'}
                        <td class="table-cell f_editable_cell" data-field-name="shipex_express_unit">{$recipient->getShipexExpressUnit()}</td>
                    {/if}
                    {if $ns.selectedFilterShowStandardUnits == 'yes' || $ns.user->getType() == 'level2'}
                    <td class="table-cell f_editable_cell" data-field-name="shipex_express_unit_1">{$recipient->getShipexExpressUnit_1()}</td>
                    {/if}
                    {if $ns.user->getType() == 'root'}
                        <td class="table-cell f_editable_cell" data-field-name="cheapex_express_unit">{$recipient->getCheapexExpressUnit()}</td>
                    {/if}
                    {if $ns.selectedFilterShowStandardUnits == 'yes' || $ns.user->getType() == 'level2'}
                    <td class="table-cell f_editable_cell" data-field-name="cheapex_express_unit_1">{$recipient->getCheapexExpressUnit_1()}</td>
                    {/if}
                    {if $ns.selectedFilterShowStandardUnits == 'yes'}
                        <td>{$recipient->getStandardUnitAddress()} {$recipient->getOnexStandardUnit()} {$recipient->getNovaStandardUnit()} {$recipient->getShipexStandardUnit()} {$recipient->getCheapexStandardUnit()}</td>
                    {/if}
                    {if $ns.user->getType() == 'root' }
                        <td class="f_editable_cell" data-field-name="document_number">{$recipient->getDocumentNumber()}</td>
                        {*                    <td>{$recipient->getDocumentType()}</td>*}
                        <td class="f_editable_cell" data-field-name="ssid">{$recipient->getSsid()}</td>
                    {/if}
                    <td class="table-cell f_editable_cell" data-field-name="note">{$recipient->getNote()}</td>
                    <td>
                        <input class="f_favorite_checkbox"
                               data-recipient_id="{$recipient->getId()}"
                               type="checkbox" value="1"
                               {if $recipient->getFavorite() ==1}checked{/if}/>
                    </td>
                    <td>
                        {if isset($ns.recipientsRecentOrdersMappedByRecipientId[$recipient->getId()])}
                            {assign recipientOrders $ns.recipientsRecentOrdersMappedByRecipientId[$recipient->getId()]}
                            <a {if $recipientOrders['total']>=$ns.recipient_monthly_limit_usd}style="color:red;"{/if} class="link" data-orders='{$recipientOrders|@json_encode|escape}'
                                                                                              href="{$SITE_PATH}/rorder/list?prt={$recipient->getId()}">
                                {$recipientOrders['count']} (${$recipientOrders['total']})

                            </a>
                        {/if}
                    </td>
                    {if $ns.user->getType() == 'root' }
                        <td class="icon-cell">
                            <a class="view_item" href="{$SITE_PATH}/recipient/{$recipient->getId()}">
                                <span class="button_icon" title="View">
                                    <i class="fa fa-eye"></i>
                                </span>
                            </a>
                        </td>
                        <td class="icon-cell">
                            <a class="view_item" href="{$SITE_PATH}/recipient/edit/{$recipient->getId()}">
                                <span class="button_icon" title="Edit">
                                    <i class="fa fa-pencil"></i>
                                </span>
                            </a>
                        </td>
                        <td class="icon-cell">
                            {if $ns.user->getType() == 'root' }
                                <input class="f_checked_checkbox" data-recipient_id="{$recipient->getId()}" type="checkbox"
                                       value="1" {if $recipient->getChecked() ==1}checked{/if}/>
                            {/if}
                        </td>
                        <td class="icon-cell">
                            {if $ns.user->getType() == 'root' }
                                <input class="f_deleted_checkbox" data-recipient_id="{$recipient->getId()}" type="checkbox"
                                       value="1" {if $recipient->getDeleted() ==1}checked{/if}/>
                            {/if}
                        </td>
                    {/if}
                </tr>
            {/foreach}
        </table>
    </div>
</div>