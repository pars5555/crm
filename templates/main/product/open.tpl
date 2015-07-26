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
    {if isset($ns.product)}
        <div>
            id: {$ns.product->getId()}
        </div>
        <div>
            Name: {$ns.product->getName()}
        </div>
        <div>
            Model: {$ns.product->getModel()}
        </div>
        <div>
            Uom: {$ns.product->getUomDto()->getName()}
        </div>
        <div>
            {if $ns.product->getManufacturerDto()}
                Manufacturer: {$ns.product->getManufacturerDto()->getName()}
            {else}
                None
            {/if}
        </div>

    {/if}
</div>
