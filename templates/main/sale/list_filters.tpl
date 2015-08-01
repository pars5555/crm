<form class="filters--form" id="saleOrderFilters" autocomplete="off" action="{SITE_PATH}/sale/list" method="GET">
    <div class="form-group">
        <label class="label">Partner</label>
        <select name="prt">
            <option value="0" {if $ns.selectedFilterPartnerId == 0}selected{/if}>All</option>
            {foreach from=$ns.partners item=p}
                <option value="{$p->getId()}" {if $ns.selectedFilterPartnerId == $p->getId()}selected{/if}>{$p->getName()}</option>
            {/foreach}
        </select>
    </div>
    <div class="form-group">
        <label class="label">Page</label>
        <select name="pg">
            {for $p=1 to $ns.pagesCount}
                <option value="{$p}" {if $ns.selectedFilterPage == $p}selected{/if}>{$p}</option>
            {/for}
        </select>
    </div>
    <div class="form-group">
        <label class="label">Sort by </label>
        <select name="srt">
            <option value="0" {if $ns.selectedFilterSortBy == 0}selected{/if}>None</option>
            {foreach from=$ns.sortFields key=fieldName item=fieldDisplayName}
                <option value="{$fieldName}" {if $ns.selectedFilterSortBy === $fieldName}selected{/if}>{$fieldDisplayName}</option>
            {/foreach}
        </select>
        <select name="ascdesc">
            <option value="ASC" {if $ns.selectedFilterSortByAscDesc== 'ASC'}selected{/if}>ASC</option>
            <option value="DESC" {if $ns.selectedFilterSortByAscDesc== 'DESC'}selected{/if}>DESC</option>
        </select>
    </div>
</form>
