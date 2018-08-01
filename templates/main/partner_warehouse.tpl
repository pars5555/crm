<div class="container warehouse--container">
    <h1>Total: {$ns.total|number_format:2}</h1>
    <div class="form-group" style="float: right">
        <a href="javascript:void(0);" id="export_csv"><img src="/img/csv.png" width="60"/></a>
    </div>
    <div class="form-group">
        <label class="label">Partner</label>
        <select id="partner_select" data-no-wrap=true>
            {foreach from=$ns.warehousePartners item=p}
                <option value="{$p->getId()}" {if $ns.selected_partner_id == $p->getId()}selected{/if}>{$p->getName()}</option>
            {/foreach}
        </select>
    </div>
    <div class="table_striped">
        <div class="table_header_group">
            <span class="table-cell">Id</span>
            <span class="table-cell">Name</span>
            <span class="table-cell">Model</span>
            <span class="table-cell">Quantity</span>
            <span class="table-cell">Purchase Orders</span>
            <span class="table-cell">Sale Orders</span>
            <span class="table-cell"> View </span>
        </div> 
        {foreach from=$ns.products item=product}
            {if isset($ns.productsQuantity[$product->getId()]) && $ns.productsQuantity[$product->getId()]>0}
                <div data-id="{$product->getId()}" data-type="product" class="table-row" > 
                    <span class="table-cell" >{$product->getId()} </span> 
                    <span class="table-cell f_editable_cell" data-field-name="name">{$product->getName()} </span>
                    <span class="table-cell f_editable_cell" data-field-name="model">{$product->getModel()} </span>
                    <span class="table-cell">{$ns.productsQuantity[$product->getId()]|default:'0'}</span>
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
                    <a class="table-cell" href="{$SITE_PATH}/product/{$product->getId()}">
                        <span class="button_icon" title="View">
                            <i class="fa fa-eye"></i>
                        </span>
                    </a>
                </div>
            {/if}
        {/foreach}
    </div>
</div>
