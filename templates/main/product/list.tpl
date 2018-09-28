<div class="container product--list--container">
    <h1 class="main_title">Products</h1>

    {if isset($ns.error_message)}
        {include file="{ngs cmd=get_template_dir}/main/message.tpl" type="error" content="{$ns.error_message}"} 
    {/if}
    {if isset($ns.success_message)}
        {include file="{ngs cmd=get_template_dir}/main/message.tpl" type="success" content="{$ns.success_message}"} 
    {/if}

    {include file="{ngs cmd=get_template_dir}/main/product/list_filters.tpl"}
    <a href="{$SITE_PATH}/product/create"><img src="{$SITE_PATH}/img/add.png"/></a>
    <div class="main-table">
        <table>
            <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Model</th>
                <th>Manufacturer</th>
                <th>Uom</th>
                <th>Purchase Orders</th>
                <th>Sale Orders</th>
                <th class="icon-cell">View</th>
                <th class="icon-cell">Edit</th>
                <th class="icon-cell">Hidden</th>
            </tr>
            {foreach from=$ns.products item=product}
                <tr>
                    <td class="link-cell id">
                        <a href="{$SITE_PATH}/product/{$product->getId()}">
                            <span>{$product->getId()} </span>
                        </a>
                    </td>
                    <td>{$product->getName()}</td>
                    <td>{$product->getModel()}</td>
                    <td>{$product->getManufacturerDto()->getName()}</td>
                    <td>{$product->getUomDto()->getName()}</td>
                    <td {if $ns.productsPurchaseOrder[$product->getId()]|@count>0}class="tooltipster"{/if}>
                        {$ns.productsPurchaseOrder[$product->getId()]|@count} Purchase order(s)
                        <p style="display: none">
                            {foreach from=$ns.productsPurchaseOrder[$product->getId()] item=productPurchaseOrders}
                                <a href="{$SITE_PATH}/purchase/{$productPurchaseOrders->getId()}">
                                    &#8470; {$productPurchaseOrders->getId()} ({$productPurchaseOrders->getOrderDate()})
                                </a> <br>
                            {/foreach}
                        </p>
                    </td>
                    <td {if $ns.productsSaleOrder[$product->getId()]|@count>0}class="tooltipster"{/if}>
                        {$ns.productsSaleOrder[$product->getId()]|@count} Sale order(s)
                        <p style="display: none">
                            {foreach from=$ns.productsSaleOrder[$product->getId()] item=productSaleOrders}
                                <a href="{$SITE_PATH}/sale/{$productSaleOrders->getId()}">
                                    &#8470; {$productSaleOrders->getId()} ({$productSaleOrders->getOrderDate()})
                                </a> <br>
                            {/foreach}
                        </p>
                    </td>
                    <td class="icon-cell">
                        <a class="view_item" href="{$SITE_PATH}/product/{$product->getId()}">
                            <span class="button_icon" title="View">
                                <i class="fa fa-eye"></i>
                            </span>
                        </a>
                    </td>
                    <td class="icon-cell">
                        <a class="view_item" href="{$SITE_PATH}/product/edit/{$product->getId()}">
                            <span class="button_icon" title="Edit">
                                <i class="fa fa-pencil"></i>
                            </span>
                        </a>
                    </td>
                    <td class="icon-cell">
                        <input class="f_hidden_checkbox"
                               data-product_id="{$product->getId()}" type="checkbox"
                               value="1" {if $product->getHidden() ==1}checked{/if}/>
                    </td>
                </tr>
            {/foreach}
        </table>
    </div>
</div>