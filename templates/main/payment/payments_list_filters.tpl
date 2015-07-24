<form id="paymentFilters" autocomplete="off" action="{SITE_PATH}/payments" method="GET">
    <h2>Filters</h2>
    <label>Partner</label>
    <select name="prt">
        <option value="0" {if $ns.selectedFilterPartnerId == 0}selected{/if}>All</option>
        {foreach from=$ns.partners item=p}
            <option value="{$p->getId()}" {if $ns.selectedFilterPartnerId == $p->getId()}selected{/if}>{$p->getName()}</option>
        {/foreach}
    </select>
    <label>Currency</label>
    <select name="cur">
        <option value="0" {if $ns.selectedFilterCurrencyId == 0}selected{/if}>All</option>
        {foreach from=$ns.currencies item=c}
            <option value="{$c->getId()}" {if $ns.selectedFilterCurrencyId == $c->getId()}selected{/if}>{$c->getTemplateChar()}</option>
        {/foreach}
    </select>
    <label>Page</label>
    <select name="pg">
        {for $p=1 to $ns.pagesCount}
            <option value="{$p}" {if $ns.selectedFilterPage == $p}selected{/if}>{$p}</option>
        {/for}
    </select>
</form>
