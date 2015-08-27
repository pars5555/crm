<div class="container warehouse--container">
    <div class="table_striped">
        <div class="table_header_group">
            <span class="table-cell">Id</span>
            <span class="table-cell">Name</span>
            <span class="table-cell">Model</span>
            <span class="table-cell">Uom</span>
            <span class="table-cell">Quantity</span>
            <span class="table-cell">Price</span>
            <span class="table-cell">Purchase Orders</span>
            <span class="table-cell">Sale Orders</span>
            <span class="table-cell"> View </span>
        </div> 
        {foreach from=$ns.products item=product}
            {if isset($ns.productsQuantity[$product->getId()]) && $ns.productsQuantity[$product->getId()]>0}
                <div class="table-row"> 
                    <span class="table-cell">{$product->getId()} </span> 
                    <span class="table-cell">{$product->getName()} </span>
                    <span class="table-cell">{$product->getModel()} </span>
                    <span class="table-cell">{$product->getUomDto()->getName()} </span>
                    <span class="table-cell">{$ns.productsQuantity[$product->getId()]|default:'0'}</span>
                    <span class="table-cell">{$ns.productsPrice[$product->getId()]|default:'NaN'}</span>
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
                                <a href="{$SITE_PATH}/sale/{$productSaleOrders->getSaleOrderId()}">
                                    &#8470; {$productSaleOrders->getSaleOrderId()} ({$productSaleOrders->getOrderDate()})
                                </a> <br>
                            {/foreach}
                        </p>
                    </span>
                    <a class="table-cell" href="{SITE_PATH}/product/{$product->getId()}">
                        <span class="button_icon" title="View">
                            <i class="fa fa-eye"></i>
                        </span>
                    </a>
                </div>
            {/if}
        {/foreach}
    </div>
</div>
