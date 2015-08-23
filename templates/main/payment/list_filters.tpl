<form class="filters--form" id="paymentFilters" autocomplete="off" action="{SITE_PATH}/payment/list" method="GET">
    {if $ns.pagesCount>0}
        <div class="form-group">
            <label>Page</label>
            <select name="pg">
                {for $p=1 to $ns.pagesCount}
                    <option value="{$p}" {if $ns.selectedFilterPage == $p}selected{/if}>{$p}</option>
                {/for}
            </select>
        </div>
    {/if}
    <div class="form-group">
        <label>Partner</label>
        <select name="prt" data-autocomplete="true">
            <option value="0" {if $ns.selectedFilterPartnerId == 0}selected{/if}>All</option>
            {foreach from=$ns.partners item=p}
                <option value="{$p->getId()}" {if $ns.selectedFilterPartnerId == $p->getId()}selected{/if}>{$p->getName()}</option>
            {/foreach}
        </select>
    </div>
    <div class="form-group">
        <label>Currency</label>
        <select name="cur">
            <option value="0" {if $ns.selectedFilterCurrencyId == 0}selected{/if}>All</option>
            {foreach from=$ns.currencies item=c}
                <option value="{$c->getId()}" {if $ns.selectedFilterCurrencyId == $c->getId()}selected{/if}>{$c->getTemplateChar()}</option>
            {/foreach}
        </select>
    </div>
    <div class="form-group">
        <label>Sort by </label>
        <select name="srt">
            <option value="0" {if $ns.selectedFilterSortBy == 0}selected{/if}>None</option>

            {foreach from=$ns.sortFields key=fieldName item=fieldDisplayName}
                {$fieldName}
                <option value="{$fieldName}" {if $ns.selectedFilterSortBy === $fieldName}selected{/if}>{$fieldDisplayName}</option>
            {/foreach}
        </select>
        <select name="ascdesc">
            <option value="ASC" {if $ns.selectedFilterSortByAscDesc== 'ASC'}selected{/if}>ASC</option>
            <option value="DESC" {if $ns.selectedFilterSortByAscDesc== 'DESC'}selected{/if}>DESC</option>
        </select>
    </div>
</form>
