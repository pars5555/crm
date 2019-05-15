<form class="filters--form" id="vanillaFilters" autocomplete="off" action="{$SITE_PATH}/vanilla/list" method="GET">
    <div class="form-group filters-group">

        <div class="filter search">
            <label>Search</label>
            <div class="search-container">
                <input class="text" style="max-width: 200px;" type="text" name="st" value="{$ns.searchText}"/>
            </div>
        </div>                
        <div class="filter search">
            <label>Balance</label>
            <div class="search-container">
                <input class="text" style="max-width: 200px;" type="text" name="bal" value="{$ns.minBalance}"/>
            </div>
        </div>  
        <div class="filter">
            <label>Show Deleted</label>
            <select name="shd">
                <option value="no" {if $ns.selectedFilterShowDeleted == 'no'}selected{/if}>No</option>
                <option value="yes" {if $ns.selectedFilterShowDeleted == 'yes'}selected{/if}>Yes</option>
            </select>
        </div>
        <button type="submit" style="visibility: hidden">search</button>
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
