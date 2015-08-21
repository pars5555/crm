<div class="container warehouse--container">
    <div class="table_striped">
        <div class="table_header_group">
            <span class="table-cell">Id</span>
            <span class="table-cell">Name</span>
            <span class="table-cell">Model</span>
            <span class="table-cell">Uom</span>
            <span class="table-cell">Quantity</span>
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
