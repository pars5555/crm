<div class="container product--list--container">
    <h1 class="main_title">Products</h1>

    {if isset($ns.error_message)}
        {include file="{ngs cmd=get_template_dir}/main/message.tpl" type="error" content="{$ns.error_message}"} 
    {/if}
    {if isset($ns.success_message)}
        {include file="{ngs cmd=get_template_dir}/main/message.tpl" type="success" content="{$ns.success_message}"} 
    {/if}

    {include file="{ngs cmd=get_template_dir}/main/product/list_filters.tpl"}

    <div class="main-table">
        <table>
            <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Model</th>
                <th>Weight</th>
                <th>Manufacturer</th>
                <th>Uom</th>
                <th>Purchase Orders</th>
                <th>Sale Orders</th>
                <th class="icon-cell">View</th>
                <th class="icon-cell">Edit</th>
                <th class="icon-cell">Hidden</th>
            </tr>
            {foreach from=$ns.products item=product}
                <tr data-id="{$product->getId()}" data-type="product">
                    <td class="link-cell id">
                        <a href="{$SITE_PATH}/product/{$product->getId()}">
                            <span>{$product->getId()} </span>
                        </a>
                    </td>
                    <td class="f_editable_cell" data-field-name="name">{$product->getName()}</td>
                    <td class="f_editable_cell" data-field-name="model">{$product->getModel()}</td>
                    <td>{$product->getUnitWeight()|number_format:2}</td>
                    <td class="f_editable_cell" data-list="brand_list" data-field-name="manufacturer">{$product->getManufacturer()}</td>
                    <td>{$product->getUomId()}</td>
                    <td {if $ns.productsPurchaseOrder[$product->getId()]|@count>0}class="tooltipster"{/if}>
                        {$ns.productsPurchaseOrder[$product->getId()]|@count} Purchase order(s)
                        <p style="display: none">
                            {foreach from=$ns.productsPurchaseOrder[$product->getId()] item=productPurchaseOrder}
                                <a href="{$SITE_PATH}/purchase/{$productPurchaseOrder->getId()}">
                                    &#8470; {$productPurchaseOrder->getId()} ({$productPurchaseOrder->getOrderDate()|truncate:10:""} {$partnersMappedByIds[$productPurchaseOrder->getPartnerId()]->getName()})
                                </a> <br>
                            {/foreach}
                        </p>
                    </td>
                    <td {if $ns.productsSaleOrder[$product->getId()]|@count>0}class="tooltipster"{/if}>
                        {$ns.productsSaleOrder[$product->getId()]|@count} Sale order(s)
                        <p style="display: none">
                            {foreach from=$ns.productsSaleOrder[$product->getId()] item=productSaleOrder}
                                <a href="{$SITE_PATH}/sale/{$productSaleOrder->getId()}">
                                    &#8470; {$productSaleOrder->getId()} ({$productSaleOrder->getOrderDate()|truncate:10:""} {$partnersMappedByIds[$productSaleOrder->getPartnerId()]->getName()}) {$productSaleOrder->getProductPrice($product->getId())}
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
        <datalist id="brand_list">
    {foreach from=$ns.brands item=brand}
        <option value="{$brand}"/>
    {/foreach}
</datalist>