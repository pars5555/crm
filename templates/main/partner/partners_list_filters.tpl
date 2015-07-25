<form id="partnerFilters" autocomplete="off" action="{SITE_PATH}/partners" method="GET">
    <h2>Filters</h2>
    <label>Page</label>
    <select name="pg">
        {for $p=1 to $ns.pagesCount}
            <option value="{$p}" {if $ns.selectedFilterPage == $p}selected{/if}>{$p}</option>
        {/for}
    </select>
    <label>Sort by </label>
    <select name="srt">
        <option value="0" {if $ns.selectedFilterSortBy== 0}selected{/if}>None</option>
        {foreach from=$ns.sortFields key=fieldName item=fieldDisplayName}
            <option value="{$fieldName}" {if $ns.selectedFilterSortBy == $fieldName}selected{/if}>{$fieldDisplayName}</option>
        {/foreach}
    </select>
    <select name="ascdesc">
        <option value="ASC" {if $ns.selectedFilterSortByAscDesc== 'ASC'}selected{/if}>ASC</option>
        <option value="DESC" {if $ns.selectedFilterSortByAscDesc== 'DESC'}selected{/if}>DESC</option>
    </select>
</form>
