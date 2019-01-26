<form class="filters--form" id="whishlistFilters" autocomplete="off" action="{$SITE_PATH}/whishlist/list" method="GET">
    <div class="form-group filters-group">
       
        {if $ns.pagesCount>0}
            <div class="filter">
                <label>Page</label>
                <select name="pg">
                    {for $p=1 to $ns.pagesCount}
                        <option value="{$p}" {if $ns.selectedFilterPage == $p}selected{/if}>{$p}</option>
                    {/for}
                </select>
            </div>
        {/if}
        <div class="filter group">
            <label>Sort by </label>
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
        
        <div class="filter">
            <div class="add-new-btn">
                <a href="{$SITE_PATH}/whishlist/create">
                    +
                </a>
            </div>
        </div>
    </div>
</form>