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
                <th>Current Min Price</th>
                <th>Note 1</th>
                <th>Note 2</th>
                <th>Item</th>
                <th>Amazon Asin List</th>
                <th>Updated Date</th>
                <th class="icon-cell">View</th>
                <th class="icon-cell">Edit</th>
                <th class="icon-cell">Delete</th>
            </tr>
            {foreach from=$ns.whishlists item=whishlist}
                <tr data-type="whishlist" data-id="{$whishlist->getId()}" {if $whishlist->getCurrentMinPrice()>0.01 && $whishlist->getCurrentMinPrice()<=$whishlist->getTargetPrice()}style="color: red"{/if}>
                    <td>{$whishlist->getId()}</td>
                    <td class="f_editable_cell" data-field-name="name">{$whishlist->getName()}</td>
                    <td class="f_editable_cell" data-field-name="target_price">{$whishlist->getTargetPrice()}</td>
                    <td>{$whishlist->getCurrentMinPrice()}</td>
                    <td class="f_editable_cell" data-field-name="note">{$whishlist->getNote()}</td>
                    <td class="f_editable_cell" data-field-name="note1">{$whishlist->getNote1()}</td>
                    <td>{if !empty($whishlist->getCurrentMinPriceAsin())} 
                        <a href="https://www.amazon.com/dp/{$whishlist->getCurrentMinPriceAsin()}" target="_blank">
                            Product amazon page
                        </a>
                        {/if}</td>
                        <td>{$whishlist->getAsinList()|replace:',':' '}</td>
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