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

        <div class="filter group">
            <label>Sort by </label>
            <select name="srt">
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
            <label>Show Deleted/Closed</label>
            <select name="shd">
                <option value="no" {if $ns.selectedFilterShowDeleted == 'no'}selected{/if}>No</option>
                <option value="yes" {if $ns.selectedFilterShowDeleted == 'yes'}selected{/if}>All</option>
            </select>
        </div>
        <div class="filter">
            <label>Partner</label>
            <select name="prt">
                <option value="0" {if $ns.selectedFilterPartnerId == '0'}selected{/if}>All</option>
                {foreach from=$ns.partners item=partner}
                    <option value="{$partner->getId()}" {if $ns.selectedFilterPartnerId == $partner->getId()}selected{/if}>{$partner->getName()}</option>
                {/foreach}
            </select>
        </div>
        <div class="filter">
            <label>Calculation Period</label>
            <select name="cms">
                <option value="0" {if $ns.selectedFilterCalculationMonths == '0'}selected{/if}>All time</option>
                <option value="1" {if $ns.selectedFilterCalculationMonths == '1'}selected{/if}>1 Month</option>
                <option value="2" {if $ns.selectedFilterCalculationMonths == '2'}selected{/if}>2 Month</option>
                <option value="3" {if $ns.selectedFilterCalculationMonths == '3'}selected{/if}>3 Month</option>
                <option value="4" {if $ns.selectedFilterCalculationMonths == '4'}selected{/if}>4 Month</option>
            </select>
        </div>
        {if $ns.user->getType() == 'root'}
            <div class="filter">
                <label>Admins</label>
                <select name="adm">
                    <option value="all" {if $ns.selectedFilterAdmin == 'all'}selected{/if}>All</option>
                    <option value="lilit" {if $ns.selectedFilterAdmin == 'lilit'}selected{/if}>Lilit</option>
                    <option value="musho" {if $ns.selectedFilterAdmin == 'musho'}selected{/if}>Musho</option>
                </select>
            </div>
        {/if}

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
