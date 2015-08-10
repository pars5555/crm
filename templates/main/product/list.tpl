<div class="container product--list--container">
    {if isset($ns.error_message)}
        <div>
            <span style="color:red">{$ns.error_message}</span>
        </div>
    {/if}
    {if isset($ns.success_message)}
        <div>
            <span style="color:green">{$ns.success_message}</span>
        </div>
    {/if}

    {include file="{getTemplateDir}/main/product/list_filters.tpl"}
<a class="button blue" href="{SITE_PATH}/product/create">create</a>
    <div class="table_striped">
        <div class="table_header_group">
            <span class="table-cell"> ID </span>
            <span class="table-cell"> Name </span>
            <span class="table-cell"> Model </span>
            <span class="table-cell"> Manufacturer </span>
            <span class="table-cell"> Uom  </span>
        </div> 
        {foreach from=$ns.products item=product}
            <div class="table-row">
                <a class="table-cell view_item" href="{SITE_PATH}/product/{$product->getId()}">
                    <span class="table-cell">{$product->getId()} </span>
                </a>
                <span class="table-cell"> {$product->getName()} </span>
                <span class="table-cell"> {$product->getModel()} </span>
                <span class="table-cell"> {$product->getManufacturerDto()->getName()} </span>
                <span class="table-cell"> {$product->getUomDto()->getName()} </span>
                <a class="table-cell view_item" href="{SITE_PATH}/product/{$product->getId()}">
                    <span class="button blue">open</span>
                </a>
                <a class="table-cell view_item" href="{SITE_PATH}/product/edit/{$product->getId()}">
                    <span class="button blue">edit</span>
                </a>
            </div>
        {/foreach}
    </div>
    

</div>