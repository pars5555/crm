<div class="container product--create--container">
    <h1>Update Product</h1>
    
    {if isset($ns.error_message)}
        {include file="{getTemplateDir}/main/message.tpl" type="error" content="{$ns.error_message}"} 
    {/if}
    {if isset($ns.success_message)}
        {include file="{getTemplateDir}/main/message.tpl" type="success" content="{$ns.success_message}"} 
    {/if}

    <form class="createProduct create--form" autocomplete="off" method="post" action="{SITE_PATH}/dyn/main_product/do_update_product">
        <div class="form-group">
            <label class="label">Name</label>
            <input class="text" type="text" name="name" value="{$ns.req.name|default:''}"/>
        </div>
        <div class="form-group">
            <label class="label">Model</label>
            <input class="text" type="text" name="model" value="{$ns.req.model|default:''}"/>
        </div>
        <div class="form-group">
            <label class="label">Manufacturer</label>
            <select name="manufacturerId">
                {if isset($ns.req.manufacturerId)}
                    {assign selectedManufacturerId $ns.req.manufacturerId}
                {else}
                    {assign selectedManufacturerId null}
                {/if}
                {foreach from=$ns.manufacturers item=m}
                    <option value="{$m->getId()}" {if isset($selectedManufacturerId) && $selectedManufacturerId == $m->getId()}selected{/if}>{$m->getName()}</option>
                {/foreach}
            </select>
        </div>
        <div class="form-group">
            <label class="label">Units of Measurement</label>
            <select name="uomId">
                {if isset($ns.req.uomId)}
                    {assign selectedUomId $ns.req.uomId}
                {else}
                    {assign selectedUomId $ns.defaultUomId}
                {/if}
                {foreach from=$ns.uoms item=u}
                    <option value="{$u->getId()}" {if $u->getId() == $selectedUomId}selected{/if}>{$u->getName()}</option>
                {/foreach}
            </select>
        </div>

        <input name="id" type="hidden" value="{$ns.product->getId()}"/>
        <input class="button blue" type="submit" value="Save"/>

    </form>
</div>