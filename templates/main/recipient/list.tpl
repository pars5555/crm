<div class="container partner--list--container">
    <h1 class="main_title">Recipients</h1>

    {if isset($ns.error_message)}
        {include file="{ngs cmd=get_template_dir}/main/message.tpl" type="error" content="{$ns.error_message}"} 
    {/if}
    {if isset($ns.success_message)}
        {include file="{ngs cmd=get_template_dir}/main/message.tpl" type="success" content="{$ns.success_message}"} 
    {/if}

    {include file="{ngs cmd=get_template_dir}/main/recipient/list_filters.tpl"}
    <a href="{$SITE_PATH}/recipient/create"><img src="{$SITE_PATH}/img/add.png"/></a>

    <div class="main-table">
        <table>
            <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Email</th>
                <th>Tel.</th>
                <th>Favorite</th>
                <th>Orders</th>
                <th class="icon-cell">View</th>
                <th class="icon-cell">Edit</th>
                <th class="icon-cell">Hidden</th>
            </tr>

            {foreach from=$ns.recipients item=recipient}
                <tr>
                    <td class="link-cell id">
                        <a href="{$SITE_PATH}/recipient/{$recipient->getId()}">
                            <span>{$recipient->getId()} </span>
                        </a>
                    </td>
                    <td>{$recipient->getName()}</td>
                    <td>{$recipient->getEmail()}</td>
                    <td style="white-space: nowrap"> {$recipient->getPhone()|replace:',':'</br>'} </td>
                    <td>
                        <input class="f_favorite_checkbox"
                               data-recipient_id="{$recipient->getId()}"
                               type="checkbox" value="1"
                               {if $recipient->getFavorite() ==1}checked{/if}/>
                    </td>
                    <td>
                        <a class="link"
                           href="{$SITE_PATH}/rorder/list?prt={$recipient->getId()}">
                            {$ns.recipientsOrdersMappedByRecipientId[$recipient->getId()]|@count}
                        </a>
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
                        <input class="f_hidden_checkbox" data-recipient_id="{$recipient->getId()}" type="checkbox"
                               value="1" {if $recipient->getHidden() ==1}checked{/if}/>
                    </td>
                </tr>
            {/foreach}
        </table>
    </div>
</div>