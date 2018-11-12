<form class="filters--form" id="warehouseFilters" autocomplete="off" action="{$SITE_PATH}/rwarehouses" method="GET">
    <div class="form-group filters-group">
        <div class="filter">
            <label>Warehouse</label>
            <select name="wh" data-autocomplete="true">
                <option value="0" {if $ns.selectedWhId== 0}selected{/if}>All</option>
                {foreach from=$ns.whs item=w}
                    <option value="{$w->getId()}" {if $ns.selectedWhId == $w->getId()}selected{/if}>{$w->getName()}</option>
                {/foreach}
            </select>
        </div>
    </div>
</form>
<div class="container sale--list--container">
    <h1 class="main_title">Warehouses</h1>

    <div class="main-table">
        <table>
            <tr>
                <th>Product Id</th>
                <th>Product</th>
                <th>Quantity</th>
            </tr>
            {foreach from=$ns.products key=productId item=data}
                <tr>
                    <td>{$productId}</td>
                    <td>{$data.product->getName()}</td>
                    <td>{$data.qty}</td>
                </tr>
            {/foreach}
        </table>
    </div>
</div>