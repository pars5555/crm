<div class="container warehouse--container">
    <h1 class="main_title">Warehouse</h1>
    {if $ns.userType == $ns.userTypeAdmin}
        <div>
            <h1 class="left">Total: {$ns.total|number_format:2}</h1>
        </div>
        <div>
            <h2 class="left">Total Stock: {$ns.total_stock|number_format:2}</h2>
        </div>
        <div class="filter csv right">
            <a href="javascript:void(0);" class="inline-block" id="export_csv"><img src="/img/csv.png" width="45"/></a>
        </div>
    {/if}
    <div class="clear"></div>
    <div class="main-table">
        <table>
            <tr>
                <th>Id</th>
                <th>Image</th>

                <th style="min-width: 250px;">Name</th>
                <th>Model</th>
                    {if $ns.userType == $ns.userTypeAdmin}
                    <th>Location</th>
                        {*                <th>Uom</th>*}
                    <th>Quantity</th>
                        {if $ns.showprofit == 1}
                        <th>Price</th>
                        {/if }
                    <th>Stock Price</th>
                    <th>Qty Checked</th>
                    <th>Purchase Orders</th>
                    <th>Sale Orders</th>
                    <th class="icon-cell">View</th>
                    {/if}
            </tr>
            {foreach from=$ns.products item=product}
                {if isset($ns.productsQuantity[$product->getId()]) && $ns.productsQuantity[$product->getId()]>0}
                    <tr data-id="{$product->getId()}" data-type="product" {if $product->getQtyChecked() == 1}style="background: lightgreen"{/if}>
                        <td>{$product->getId()}</td>
                        <td> <img src="{$product->getImageUrl()}" width="100"/> </td>
                        <td style="min-width: 250px;" data-field-name="name">{$product->getName()}</td>
                        <td data-field-name="model">{$product->getModel()}</td>
                        {if $ns.userType == $ns.userTypeAdmin}
                            <td class="pre f_editable_cell" data-type="richtext"  data-field-name="location_note">{$product->getLocationNote()} </td>
                            {*                        <td>{$product->getUomDto()->getName()}</td>*}
                            <td>{$ns.productsQuantity[$product->getId()]|default:'0'}</td>
                            {if $ns.showprofit == 1}
                                <td>{$ns.productsPrice[$product->getId()]|number_format:2}</td>
                            {/if}
                            <td class="f_editable_cell" data-field-name="stock_price">{$product->getStockPrice()|number_format:2}</td>                            
                            <td class="icon-cell">
                                <input class="f_qty_checked_checkbox"
                                       data-product_id="{$product->getId()}" type="checkbox"
                                       value="1" {if $product->getQtyChecked() ==1}checked{/if}/>
                            </td>
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
                                            &#8470; {$productSaleOrder->getId()} ({$productSaleOrder->getOrderDate()|truncate:10:""}  {$partnersMappedByIds[$productSaleOrder->getPartnerId()]->getName()})
                                        </a> <br>
                                    {/foreach}
                                </p>
                            </td>
                            <td class="icon-cell">
                                <a href="{$SITE_PATH}/product/{$product->getId()}">
                                    <span class="button_icon" title="View">
                                        <i class="fa fa-eye"></i>
                                    </span>
                                </a>
                            </td>
                        {/if}
                    </tr>
                {/if}
            {/foreach}
        </table>
    </div>
</div>
