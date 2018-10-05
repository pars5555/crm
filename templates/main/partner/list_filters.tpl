<form class="filters--form" id="partnerFilters" autocomplete="off" action="{$SITE_PATH}/partner/list" method="GET">
    <div class="form-group filters-group">
        <div class="filter">
            <label>Search</label>
            <div class="search-container">
                <input class="text" type="text" name="st" value="{$ns.searchText}"/>
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
            <label>Show Hidden</label>
            <select name="hddn">
                <option value="all" {if $ns.selectedFilterHidden == 'all'}selected{/if}>All</option>
                <option value="no" {if $ns.selectedFilterHidden == 'no'}selected{/if}>No</option>
            </select>
        </div>
        <div class="filter">
            <label>Debt</label>
            <select name="hasdebt">
                <option value="all" {if $ns.selectedFilterHasDebt == 'all'}selected{/if}>All</option>
                <option value="yes" {if $ns.selectedFilterHasDebt == 'yes'}selected{/if}>Yes</option>
                <option value="no" {if $ns.selectedFilterHasDebt == 'no'}selected{/if}>No</option>
            </select>
        </div>
        <div class="filter csv">
            <a href="javascript:void(0);" class="inline-block" id="export_csv"><img src="/img/csv.png" width="45"/></a>
        </div>
        <div class="filter">
            <div class="add-new-btn">
                <a href="{$SITE_PATH}/partner/create">
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
