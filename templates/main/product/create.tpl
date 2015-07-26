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
    <form class="createProduct" autocomplete="off" method="post" action="{SITE_PATH}/dyn/main/do_create_product">
        <div>
            <label>Name</label>
            <input type="text" name="name" value="{$ns.req.name|default:''}"/>
        </div>
        <div>
            <label>Model</label>
            <input type="text" name="model" value="{$ns.req.model|default:''}"/>
        </div>
        <div>
            <label>Manufacturer</label>
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
            <div>
            <label>Units of Measurement</label>
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
        
        <input type="submit" value="Save"/>

    </form>
</div>