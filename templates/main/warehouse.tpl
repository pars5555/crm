<form class="filters--form" id="warehouseFilters" autocomplete="off" action="{$SITE_PATH}/warehouse/list" method="GET">
    <div class="filter group">
        <label>Sort by </label>
        <select name="srt">
            <option value="none" {if $ns.selectedFilterSortBy == 0}selected{/if}>None</option>

            {foreach from=$ns.sortFields key=fieldName item=fieldDisplayName}
                {$fieldName}
                <option value="{$fieldName}" {if $ns.selectedFilterSortBy === $fieldName}selected{/if}>{$fieldDisplayName}</option>
            {/foreach}
        </select>
        <select name="ascdesc">
            <option value="ASC" {if $ns.selectedFilterSortByAscDesc== 'ASC'}selected{/if}>ASC</option>
            <option value="DESC" {if $ns.selectedFilterSortByAscDesc== 'DESC'}selected{/if}>DESC</option>
        </select>
    </div>
</form>
<div class="container warehouse--container">
    <h1 class="main_title">Warehouse</h1>
    {if $ns.userType == $ns.userTypeAdmin || $ns.vahagn_cookie === 'Vahagn123'}
        <h1 class="left">Total: {$ns.total|number_format:2}</h1>
        <br/><br/>
        <h2 class="left">Total Stock: {$ns.total_stock|number_format:2}</h2>
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
                <th>Brand</th>
                <th>Model</th>
                <th>Category</th>
                <th>Sale Price</th>
                    {if $ns.userType == $ns.userTypeAdmin}
                    <th>Location</th>
                        {*                <th>Uom</th>*}
                    <th>Quantity</th>
                    <th>Reserved Qty</th>
                        {if $ns.showprofit == 1}
                        <th>Price</th>
                        {/if }
                    <th>Stock Price</th>
                    <th>Qty Checked</th>
                        {if $ns.userType == $ns.userTypeAdmin}
                        <th>Purchase Orders</th>
                        <th>Sale Orders</th>
                        {/if}
                    <th class="icon-cell">View</th>
                    {/if}
            </tr>
            {assign cat 0}
            {foreach from=$ns.products item=product}
                {if $ns.selectedFilterSortBy === 'none'}
                    {if $cat != $product->getCategoryId()}
                        {assign cat $product->getCategoryId()}
                        <tr>
                            <td></td>
                            <td style="color:red; font-size: 24px">{$ns.categoriesMappedById[$product->getCategoryId()]}</td>
                        </tr>
                    {/if}
                {/if}
                {if (isset($ns.productsQuantity[$product->getId()]) && $ns.productsQuantity[$product->getId()]>0) || (isset($ns.pwarehousesProductsQuantity[$product->getId()]) && $ns.pwarehousesProductsQuantity[$product->getId()]>0)}
                    <tr data-id="{$product->getId()}" data-type="product" {if $product->getQtyChecked() == 1}style="background: lightgreen"{/if}>
                        <td>{$product->getId()}</td>
                        <td> <img src="{$product->getImageUrl()}" width="100"/> </td>
                        <td style="min-width: 250px; {if $product->getId()|in_array:$ns.newProductIds} color:blue; {/if}" data-field-name="name">{$product->getName()}</td>
                        <td class="f_editable_cell" data-list="brand_list" data-field-name="manufacturer">{$product->getManufacturer()}</td>
                        <td class="f_editable_cell" data-list="model_list" data-field-name="model">{$product->getModel()}</td>
                        <td class="f_selectable_cell" data-value="{$product->getCategoryId()}" data-field-name="category_id" data-template-select-id="category_select">
                            {$ns.categoriesMappedById[$product->getCategoryId()]}
                        </td>
                        <td {if $ns.userType == $ns.userTypeAdmin || $ns.vahagn_cookie === 'Vahagn123'}class="f_editable_cell" data-field-name="sale_price"{/if}>{$product->getSalePrice()|number_format:2}</td>                            
                        {if $ns.userType == $ns.userTypeAdmin || $ns.vahagn_cookie === 'Vahagn123'}
                            {if $ns.userType == $ns.userTypeAdmin}
                                <td class="pre f_editable_cell" data-type="richtext"  data-field-name="location_note">{$product->getLocationNote()}
                                </td>
                            {/if}
                            <td>
                                {if isset($ns.productsQuantity[$product->getId()])}
                                    {assign qty $ns.productsQuantity[$product->getId()]|default:0}
                                    {if isset($ns.pwarehousesProductsQuantity[$product->getId()])}
                                        {assign qty $qty-$ns.pwarehousesProductsQuantity[$product->getId()]}
                                    {/if}
                                    {$qty}<br/>
                                {/if}
                                {if isset($ns.pwarehousesProductsQuantity[$product->getId()])}
                                    <span style="color:red">{$pwarehousesProductsQuantity[$product->getId()]|default:'0'}</span>
                                {/if}

                            </td>
                            <td {if !empty($ns.reservations[$product->getId()])}class="tooltipster"{/if}>
                                {if isset($ns.reservations[$product->getId()])}
                                    {$ns.reservations[$product->getId()]|@count} Reservation
                                {/if}
                                <p style="display: none">
                                    {foreach from=$ns.reservations[$product->getId()] item=productReservation}
                                        <a href="javascript:void(0);">
                                            qty: {$productReservation->getQuantity()}, ({$productReservation->getStartAt()|truncate:10:""} - {$productReservation->getHours()} hours), tel: {$productReservation->getPhone()}
                                            <br>
                                            {$productReservation->getNote()}
                                        </a>
                                        <br>
                                    {/foreach}
                                </p>
                                </br>
                                <span class="button_icon f_reserve" title="Reserve" data-product_id="{$product->getId()}" data-product_name="{$product->getName()}">
                                    <i class="fa fa-calendar"></i>
                                </span>

                            </td>

                            {if $ns.showprofit == 1}
                                <td>
                                    {if isset($ns.productsPrice[$product->getId()])}
                                        {$ns.productsPrice[$product->getId()]|number_format:2}
                                    {else}
                                        <span style="color:red">partner</span>
                                    {/if}
                                </td>
                            {/if}

                            <td class="f_editable_cell" {if isset($ns.productsPrice[$product->getId()]) && $product->getStockPrice()<=$ns.productsPrice[$product->getId()]}style="color:orange"{/if} 
                                data-field-name="stock_price">{$product->getStockPrice()|number_format:2}</td>                            

                            <td class="icon-cell">
                                <input class="f_qty_checked_checkbox"
                                       data-product_id="{$product->getId()}" type="checkbox"
                                       value="1" {if $product->getQtyChecked() ==1}checked{/if}/>
                            </td>
                            {if $ns.userType == $ns.userTypeAdmin}
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
                                                &#8470; {$productSaleOrder->getId()} ({$productSaleOrder->getOrderDate()|truncate:10:""}  {$partnersMappedByIds[$productSaleOrder->getPartnerId()]->getName()}) {$productSaleOrder->getProductPrice($product->getId())}
                                            </a> <br>
                                        {/foreach}
                                    </p>
                                </td>
                            {/if}
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
<select id='category_select' class="hidden" style="width: 120px" >
    {foreach from=$ns.categoriesMappedById key=category_id item=category_name}
        <option value="{$category_id}">{$category_name}</option>
    {/foreach}
</select>

<div id="add_reservation_modalBox" class="modal modal-large">
    <div class="modal-container">
        <div class="modal-inner-container" >
            <span class="modal-close">
                <span class="close-icon1"></span>
                <span class="close-icon2"></span>
            </span>
            <h1 class="modal-headline">Product Reservation</h1>
            <h2 class="f_product_name"></h2>
            <input type="hidden" id="reserve_product_id"/>
            <div class="modal-content">
                <div class="message {$type}-msg">
                    <span class="fontAwesome msg-icon"></span>
                    <span class="f_error_message"></span>
                </div>


                <label>Quantity</label>
                <select class="text" id="reserve_qty" style="width:100%">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                </select>
                <br/>
                <select class="text" id="reserve_hours" style="width:100%">
                    <option value="3">3 hours</option>
                    <option value="6">6 hours</option>
                    <option value="12">12 hours</option>
                    <option value="24">24 hours</option>
                    <option value="48">48 hours</option>
                </select>
                <br/>
                <label>Phone Number</label>
                <input class="text" id="reserve_phone_number" style="width:100%" type="text"/>
                <br/>
                <label>Note</label>
                <textarea id="reserve_note" class="text" rows="5" style="width:100%"></textarea>
                <br/>
                <a class="button blue" id="reserve_confirm" href="javascript:void(0);" >Confirm</a>

            </div>
        </div>
    </div>
</div>

<datalist id="model_list">
    {foreach from=$ns.models item=model}
        <option value="{$model}"/>
    {/foreach}
</datalist>
<datalist id="brand_list">
    {foreach from=$ns.brands item=brand}
        <option value="{$brand}"/>
    {/foreach}
</datalist>
