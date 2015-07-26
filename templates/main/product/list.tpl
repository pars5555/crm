<div>
    {include file="{getTemplateDir}/main/left_menu.tpl"}
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
    
    <a class="button" href="{SITE_PATH}/product/create">create</a>
    {include file="{getTemplateDir}/main/product/list_filters.tpl"}
    <div>
        <span> ID </span>
        <span> DATE </span>
        <span> Partner </span>
        <span> Payment Method </span>
        <span> Amount </span>
    </div> 
    {foreach from=$ns.products item=product}
        <div>
            <a href="{SITE_PATH}/product/{$product->getId()}">{$product->getId()} </a>
            <span> {$product->getName()} </span>
            <span> {$product->getModel()} </span>
            <span> {$product->getManufacturerDto()->getName()} </span>
            <span> {$product->getUomDto()->getName()} </span>

        </div>
    {/foreach}

</div>