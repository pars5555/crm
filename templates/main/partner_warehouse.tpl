<div class="container warehouse--container">
    <h1>Total:
        {foreach from=$ns.total key=currencyId item=amount}
            <span class="price">
                {assign currencyDto $ns.currencies[$currencyId]}
                {if $currencyDto->getSymbolPosition() == 'left'}
                    {$currencyDto->getTemplateChar()}
                {/if}
                {$amount}
                {if $currencyDto->getSymbolPosition() == 'right'}
                    {$currencyDto->getTemplateChar()}
                {/if}
            </span>
        {/foreach}</h1>
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
    <div class="main-table">
        <table>
            <thead class="table_header_group">
                <th>Id</th>
                <th>Name</th>
                <th>Model</th>
                <th>Quantity</th>
                <th>Last Sale Price</th>
                <th>Purchase Orders</th>
                <th>Sale Orders</th>
                <th> View </th>
        </thead>
            {foreach from=$ns.products item=product}
                {if isset($ns.productsQuantity[$product->getId()]) && abs($ns.productsQuantity[$product->getId()])>0.01}
                    {assign lastSale $ns.productsSaleOrder[$product->getId()]|@end}
                    
                    <tr data-id="{$product->getId()}" data-type="product" {if $ns.productsQuantity[$product->getId()]<0 or $lastSale->getOrderDateDiffWithNow()>60}style="color:red"{/if} >
                        <td>{$product->getId()}</td>
                        <td class="f_editable_cell" data-field-name="name">{$product->getName()} </td>
                        <td class="f_editable_cell" data-field-name="model">{$product->getModel()} </td>
                        <td>{$ns.productsQuantity[$product->getId()]|default:'0'}</td>
                        <td class="f_editable_cell" data-field-name="stock_price">{$ns.productLastSellPrice[$product->getId()]|number_format:2}</td>  
                        <td {if $ns.productsPurchaseOrder[$product->getId()]|@count>0}class="tooltipster"{/if}>
                            {$ns.productsPurchaseOrder[$product->getId()]|@count} Purchase order(s)
                            <p style="display: none">
                                {foreach from=$ns.productsPurchaseOrder[$product->getId()] item=productPurchaseOrder}
                                    <a href="{$SITE_PATH}/purchase/{$productPurchaseOrder->getId()}">
                                        &#8470; {$productPurchaseOrder->getId()} ({$productPurchaseOrder->getOrderDate()})
                                    </a> <br>
                                {/foreach}
                            </p>
                        </td>
                        <td {if $ns.productsSaleOrder[$product->getId()]|@count>0}class="tooltipster"{/if}>
                            {$ns.productsSaleOrder[$product->getId()]|@count} Sale order(s)
                            <p style="display: none">
                                {foreach from=$ns.productsSaleOrder[$product->getId()] item=productSaleOrder}
                                    <a href="{$SITE_PATH}/sale/{$productSaleOrder->getId()}">
                                        &#8470; {$productSaleOrder->getId()} ({$productSaleOrder->getOrderDate()})
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
                    </tr>
                {/if}
            {/foreach}
        </table>
    </div>
</div>
