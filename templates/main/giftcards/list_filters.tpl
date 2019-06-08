<form class="filters--form" id="giftcardsFilters" autocomplete="off" action="{$SITE_PATH}/giftcards/list" method="GET">
    <div class="form-group filters-group">

        <div class="filter search">
            <label>Search</label>
            <div class="search-container">
                <input class="text" style="max-width: 200px;" type="text" name="st" value="{$ns.searchText}"/>
            </div>
        </div>                
        <div class="filter">
            <label>Partner</label>
            <select name="pid">
                <option value="0" {if $ns.selectedFilterPartnerId == 0}selected{/if}>All</option>
                {foreach from=$ns.supplier_partners_mapped_by_ids item=p}
                    <option value="{$p->getId()}" {if $ns.selectedFilterPartnerId == $p->getId()}selected{/if}>{$p->getName()}</option>
                {/foreach}
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
