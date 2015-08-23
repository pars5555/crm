<div class="container product--list--container">
    <h1>Products</h1>
    
    {if isset($ns.error_message)}
        {include file="$TEMPLATE_DIR/main/message.tpl" type="error" content="{$ns.error_message}"} 
    {/if}
    {if isset($ns.success_message)}
        {include file="$TEMPLATE_DIR/main/message.tpl" type="success" content="{$ns.success_message}"} 
    {/if}

    {include file="{getTemplateDir}/main/product/list_filters.tpl"}
    <a href="{SITE_PATH}/product/create"><img src="{SITE_PATH}/img/add.png"/></a>
    <div class="table_striped">
        <div class="table_header_group">
            <span class="table-cell"> ID </span>
            <span class="table-cell"> Name </span>
            <span class="table-cell"> Model </span>
            <span class="table-cell"> Manufacturer </span>
            <span class="table-cell"> Uom  </span>
            <span class="table-cell"> View  </span>
            <span class="table-cell"> Edit </span>
        </div> 
        {foreach from=$ns.products item=product}
            <div class="table-row">
                <a class="table-cell" href="{SITE_PATH}/product/{$product->getId()}">
                    <span>{$product->getId()} </span>
                </a>
                <span class="table-cell"> {$product->getName()} </span>
                <span class="table-cell"> {$product->getModel()} </span>
                <span class="table-cell"> {$product->getManufacturerDto()->getName()} </span>
                <span class="table-cell"> {$product->getUomDto()->getName()} </span>
                <a class="table-cell view_item" href="{SITE_PATH}/product/{$product->getId()}">
                    <span class="button_icon" title="View">
                        <i class="fa fa-eye"></i>
                    </span>
                </a>
                <a class="table-cell view_item" href="{SITE_PATH}/product/edit/{$product->getId()}">
                    <span class="button_icon" title="Edit">
                        <i class="fa fa-pencil"></i>
                    </span>
                </a>
            </div>
        {/foreach}
    </div>


</div>