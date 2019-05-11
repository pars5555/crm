<form class="filters--form" id="productFilters" autocomplete="off" action="{$SITE_PATH}/vanilla/list" method="GET">
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
