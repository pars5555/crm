<div>
    {include file="{getTemplateDir}/main/left_menu.tpl"}
        <div> 
            <span>Id</span>
            <span>Name</span>
            <span>Model</span>
            <span>Uom</span>
            <span>Quantity</span>
        </div> 
    {foreach from=$ns.products item=product}
        <div> 
            <a href="{SITE_PATH}/product/{$product->getId()}">{$product->getId()} </a> 
            <span>{$product->getName()} </span>
            <span>{$product->getModel()} </span>
            <span>{$product->getUomDto()->getName()} </span>
            <span>{$ns.productsQuantity[$product->getId()]|default:'0'}</span>
        </div>

    {/foreach}
</div>
