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
    <div class="table_striped">
        <div class="table_header_group">
            <span class="table-cell"> ID </span>
            <span class="table-cell"> Name </span>
            <span class="table-cell"> Model </span>
            <span class="table-cell"> Manufacturer </span>
            <span class="table-cell"> Uom </span>
            <span class="table-cell"> Purchase Orders </span>
            <span class="table-cell"> Sale Orders</span>
            <span class="table-cell"> View </span>
            <span class="table-cell"> Edit </span>
            <span class="table-cell"> Hidden </span>
        </div> 
        {foreach from=$ns.products item=product}
            <div class="table-row">
                <a class="table-cell" href="{$SITE_PATH}/product/{$product->getId()}">
                    <span>{$product->getId()} </span>
                </a>
                <span class="table-cell"> {$product->getName()} </span>
                <span class="table-cell"> {$product->getModel()} </span>
                <span class="table-cell"> {$product->getManufacturerDto()->getName()} </span>
                <span class="table-cell"> {$product->getUomDto()->getName()} </span>
                <span class="table-cell {if $ns.productsPurchaseOrder[$product->getId()]|@count>0}tooltipster{/if}">
                    {$ns.productsPurchaseOrder[$product->getId()]|@count} Purchase order(s)
                    <p style="display: none">
                        {foreach from=$ns.productsPurchaseOrder[$product->getId()] item=productPurchaseOrders}
                            <a href="{$SITE_PATH}/purchase/{$productPurchaseOrders->getId()}">
                                &#8470; {$productPurchaseOrders->getId()} ({$productPurchaseOrders->getOrderDate()})
                            </a> <br>
                        {/foreach}
                    </p>
                </span>
                <span class="table-cell {if $ns.productsSaleOrder[$product->getId()]|@count>0}tooltipster{/if}">
                    {$ns.productsSaleOrder[$product->getId()]|@count} Sale order(s)
                    <p style="display: none">
                        {foreach from=$ns.productsSaleOrder[$product->getId()] item=productSaleOrders}
                            <a href="{$SITE_PATH}/sale/{$productSaleOrders->getId()}">
                                &#8470; {$productSaleOrders->getId()} ({$productSaleOrders->getOrderDate()})
                            </a> <br>
                        {/foreach}
                    </p>
                </span>

                <a class="table-cell view_item" href="{$SITE_PATH}/product/{$product->getId()}">
                    <span class="button_icon" title="View">
                        <i class="fa fa-eye"></i>
                    </span>
                </a>
                <a class="table-cell view_item" href="{$SITE_PATH}/product/edit/{$product->getId()}">
                    <span class="button_icon" title="Edit">
                        <i class="fa fa-pencil"></i>
                    </span>
                </a>
                     <span class="table-cell "> <input class="f_hidden_checkbox" data-product_id="{$product->getId()}" type="checkbox" value="1" {if $product->getHidden() ==1}checked{/if}/></span>
            </div>
        {/foreach}
    </div>


</div>