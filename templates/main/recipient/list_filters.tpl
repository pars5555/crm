<form class="filters--form" id="recipientFilters" autocomplete="off" action="{$SITE_PATH}/recipient/list" method="GET">
    <div class="form-group filters-group">
         <div class="filter search">
            <label>Search</label>
            <div class="search-container">
                <input class="text" style="max-width: 200px;" type="text" name="st" value="{$ns.searchText}"/>
            </div>
        </div>
        <div class="filter group">
            <label>Sort by </label>
            <select name="srt">
                <option value="0" {if $ns.selectedFilterSortBy== 0}selected{/if}>None</option>
                {foreach from=$ns.sortFields key=fieldName item=fieldDisplayName}
                    <option value="{$fieldName}" {if $ns.selectedFilterSortBy === $fieldName}selected{/if}>{$fieldDisplayName}</option>
                {/foreach}
            </select>
            <select name="ascdesc">
                <option value="ASC" {if $ns.selectedFilterSortByAscDesc== 'ASC'}selected{/if}>ASC</option>
                <option value="DESC" {if $ns.selectedFilterSortByAscDesc== 'DESC'}selected{/if}>DESC</option>
            </select>
        </div>
        <div class="filter">
            <label>Show Dleted</label>
            <select name="del">
                <option value="all" {if $ns.selectedFilterDeleted == 'all'}selected{/if}>All</option>
                <option value="no" {if $ns.selectedFilterDeleted == 'no'}selected{/if}>No</option>
            </select>
        </div>
        <div class="filter wide text-right">
            <div class="add-new-btn">
                <a href="{$SITE_PATH}/recipient/create">
                    +
                </a>
            </div>
        </div>
    </div>

    {if $ns.pagesCount > 0}
        <div class="form-group table-pagination">
            <label>Page</label>
            <select name="pg">
                {for $p=1 to $ns.pagesCount}
                    <option value="{$p}" {if $ns.selectedFilterPage == $p}selected{/if}>{$p}</option>
                {/for}
            </select>
        </div>
    {/if}
</form>
