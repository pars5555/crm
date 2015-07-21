<div>
    <h2>Filters</h2>
    <label>Partner</label>
    <select name="filterPartnerId">
        {foreach from=$ns.partners item=p}
            <option value="{$p->getId()}" {if isset($ns.selectedFilterPartnerId) && $ns.selectedFilterPartnerId == $p->getId()}selected{/if}>{$p->getName()}</option>
        {/foreach}
    </select>
</div>
