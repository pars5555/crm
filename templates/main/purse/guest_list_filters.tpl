<form class="filters--form" id="productFilters" autocomplete="off" action="{$SITE_PATH}/purse/checkout" method="GET">
    {if $ns.pagesCount > 0}
        <div class="form-group">     
            <label>Page</label>
            <select name="pg">
                {for $p=1 to $ns.pagesCount}
                    <option value="{$p}" {if $ns.selectedFilterPage == $p}selected{/if}>{$p}</option>
                {/for}
            </select>
        </div>
    {/if}
    <div class="form-group" style="display: -webkit-box;">     
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

        <label>Status</label>
        <select name="stts">
            <option value="all" {if $ns.selectedFilterStatus == 'all'}selected{/if}>All</option>
            <option value="active" {if $ns.selectedFilterStatus == 'active'}selected{/if}>Active</option>
            <option value="inactive" {if $ns.selectedFilterStatus == 'inactive'}selected{/if}>Archive</option>
        </select>


        <label>Show Hiddens</label>
        <select name="hddn">
            <option value="all" {if $ns.selectedFilterHidden == 'all'}selected{/if}>All</option>
            <option value="no" {if $ns.selectedFilterHidden == 'no'}selected{/if}>No</option>
        </select>

        <label>Search</label>
        <input class="text" style="max-width: 200px;" type="text" name="st" value="{$ns.searchText}"/>
    </div>
</form>