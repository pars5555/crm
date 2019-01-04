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
                <th>Email</th>
                    {*                <th>Tel.</th>*}
                <th>Ex Unit</th>
                <th>St Unit</th>
                <th>Doc Number</th>
                    {*                <th>Doc Type</th>*}
                <th>Note</th>
                <th>Favorite</th>
                <th>Orders</th>
                <th class="icon-cell">View</th>
                <th class="icon-cell">Edit</th>
                <th class="icon-cell">Checked</th>
                <th class="icon-cell">Deleted</th>
            </tr>

            {foreach from=$ns.recipients item=recipient}
                <tr class="table-row"  style="{if $recipient->getDeleted() ==1}background:lightgrey{elseif $recipient->getChecked() == 1}background:green{/if}"  data-type="recipient" data-id="{$recipient->getId()}">
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
                    <td>{$recipient->getEmail()}</td>
                    {*                    <td style="white-space: nowrap"> {$recipient->getPhoneNumber()|replace:',':'</br>'} </td>*}
                    <td>{$recipient->getExpressUnitAddress()} {$recipient->getOnexExpressUnit()} {$recipient->getNovaExpressUnit()}</td>
                    <td>{$recipient->getStandardUnitAddress()} {$recipient->getOnexStandardUnit()} {$recipient->getNovaStandardUnit()}</td>
                    <td>{$recipient->getDocumentNumber()}</td>
                    {*                    <td>{$recipient->getDocumentType()}</td>*}
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

                            <a class="link" data-orders='{$recipientOrders|@json_encode}'
                               href="{$SITE_PATH}/rorder/list?prt={$recipient->getId()}">
                                {$recipientOrders['count']} (${$recipientOrders['total']})

                            </a>
                        {/if}
                    </td>
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
                        <input class="f_checked_checkbox" data-recipient_id="{$recipient->getId()}" type="checkbox"
                               value="1" {if $recipient->getChecked() ==1}checked{/if}/>
                    </td>
                    <td class="icon-cell">
                        <input class="f_deleted_checkbox" data-recipient_id="{$recipient->getId()}" type="checkbox"
                               value="1" {if $recipient->getDeleted() ==1}checked{/if}/>
                    </td>
                </tr>
            {/foreach}
        </table>
    </div>
</div>