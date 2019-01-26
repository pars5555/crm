<div class="container whishlist--list--container">
    <h1 class="main_title">Whishlists</h1>

    {if isset($ns.error_message)}
        {include file="{ngs cmd=get_template_dir}/main/message.tpl" type="error" content="{$ns.error_message}"}
    {/if}
    {if isset($ns.success_message)}
        {include file="{ngs cmd=get_template_dir}/main/message.tpl" type="success" content="{$ns.success_message}"}
    {/if}

    {include file="{ngs cmd=get_template_dir}/main/whishlist/list_filters.tpl"}
    <div class="main-table">
        <table>
            <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Target Price</th>
                <th>Amazon Asin List</th>
                <th>Updated Date</th>
                <th class="icon-cell">View</th>
                <th class="icon-cell">Edit</th>
                <th class="icon-cell">Delete</th>
            </tr>
            {foreach from=$ns.whishlists item=whishlist}
                <tr>
                    <td>{$whishlist->getId()}</td>
                    <td>{$whishlist->getName()}</td>
                    <td>{$whishlist->getTargetPrice()}</td>
                    <td>{$whishlist->getAsinList()}</td>
                    <td>{$whishlist->getUpdatedAt()}</td>
                    <td class="icon-cell">
                        <a href="{$SITE_PATH}/whishlist/{$whishlist->getId()}">
                            <span class="button_icon" title="View">
                                <i class="fa fa-eye"></i>
                            </span>
                        </a>
                    </td>
                    <td class="icon-cell">
                        <a href="{$SITE_PATH}/whishlist/edit/{$whishlist->getId()}">
                            <span class="button_icon" title="Edit">
                                <i class="fa fa-pencil"></i>
                            </span>
                        </a>
                    </td>
                    <td class="icon-cell">
                        <a class="deleteWhishlist" href="{$SITE_PATH}/dyn/main_whishlist/do_delete_whishlist?id={$whishlist->getId()}">
                            <span class="button_icon" title="delete">
                                <i class="fa fa-trash-o"></i>
                            </span>
                        </a>
                    </td>
                </tr>
            {/foreach}
        </table>
    </div>
</div>