<div class="container product--open--container">
    <h1 class="main_title">Product View</h1>
    
    {if isset($ns.error_message)}
        {include file="{getTemplateDir}/main/message.tpl" type="error" content="{$ns.error_message}"} 
    {/if}
    {if isset($ns.success_message)}
        {include file="{getTemplateDir}/main/message.tpl" type="success" content="{$ns.success_message}"} 
    {/if}

    {if isset($ns.product)}
        <div class="table_striped table_striped_simple">
            <div class="table-row">
                <span class="table-cell">
                    id :
                </span>
                <span class="table-cell">
                    {$ns.product->getId()}
                </span>
            </div>
            <div class="table-row">
                <span class="table-cell">
                    Name :
                </span>
                <span class="table-cell">
                    {$ns.product->getName()}
                </span>
            </div>
            <div class="table-row">
                <span class="table-cell">
                    Model :
                </span>
                <span class="table-cell">
                    {$ns.product->getModel()}
                </span>
            </div>
            <div class="table-row">
                <span class="table-cell">
                    Uom :
                </span>
                <span class="table-cell">
                    {$ns.product->getUomDto()->getName()}
                </span>
            </div>
            <div class="table-row">
                <span class="table-cell">
                    Manufacturer : 
                </span>
                <span class="table-cell">
                    {if $ns.product->getManufacturerDto()}
                        {$ns.product->getManufacturerDto()->getName()}
                    {else}
                        <span class="text_red">None</span>
                    {/if}
                </span>
            </div>
            <div class="table-row">
                <span class="table-cell">
                    Quantity in warehouse :
                </span>
                <span class="table-cell">
                    {$ns.productQuantity}
                </span>
            </div>
        </div>
    {/if}
    <a class="button blue deleteProductButton"  href="{SITE_PATH}/dyn/main_product/do_delete_product?id={$product->getId()}">
        <span>delete</span>
    </a>
</div>
