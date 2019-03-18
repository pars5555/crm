<form class="filters--form" id="productFilters" autocomplete="off" action="{$SITE_PATH}/checkout/list" method="GET">
    <div class="form-group filters-group">
        <div class="filter">
            <label>Recipient</label>
            <select name="rcpt" data-autocomplete="true">
                <option value="0" {if $ns.selectedFilterRecipientId == 0}selected{/if}>All</option>
                {foreach from=$ns.recipients item=p}
                    <option value="{$p->getId()}" {if $ns.selectedFilterRecipientId == $p->getId()}selected{/if}>{$p->getFirstName()} {$p->getLastName()}</option>
                {/foreach}
            </select>
        </div>
        <div class="filter search">
            <label>Search</label>
            <div class="search-container">
                <input class="text" style="max-width: 200px;" type="text" name="st" value="{$ns.searchText}"/>
            </div>
        </div>
        <div class="filter group">
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
        </div>

        <div class="filter">
            <label>Status</label>
            <select name="stts">
                <option value="all" {if $ns.selectedFilterStatus == 'all'}selected{/if}>All</option>
                <option value="active" {if $ns.selectedFilterStatus == 'active'}selected{/if}>Active</option>
                <option value="inactive" {if $ns.selectedFilterStatus == 'inactive'}selected{/if}>Archive</option>
            </select>
        </div>

        <div class="filter">
            <label>Type (Ex/BTC)</label>
            <select name="tp">
                <option value="all" {if $ns.orderType == 'all'}selected{/if}>All</option>
                <option value="external" {if $ns.orderType == 'external'}selected{/if}>External</option>
                <option value="btc" {if $ns.orderType == 'btc'}selected{/if}>Btc</option>
            </select>
        </div>
        <div class="filter">
            <label>Active/Archive</label>
            <select name="chst">
                <option value="all" {if $ns.selectedFilterCheckoutStatus== 'all'}selected{/if}>All</option>
                <option value="active" {if $ns.selectedFilterCheckoutStatus == 'active'}selected{/if}>Active Only</option>
                <option value="inactive" {if $ns.selectedFilterCheckoutStatus == 'inactive'}selected{/if}>Archive Only</option>
            </select>
        </div>
        <div class="filter">
            <label>Problematic</label>
            <input name="pr" type="checkbox" name="pr" {if $ns.problematic == 1}checked{/if} value="1"/>
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
    <div class="filter csv right">
        <a href="javascript:void(0);" class="inline-block" id="export_csv"><img src="{$SITE_PATH}/img/csv.png" width="45"/></a>
    </div>
</form>
<h2>
    Rows Count: {$ns.count}
</h2>
{if isset($ns.total_puposed_to_not_received)}
    <h4>
        Total not received to recipient: {$ns.total_puposed_to_not_received}
    </h4>
{/if}
{if isset($ns.not_received_orders_count)}
    <h4>
        Total not received count: {$ns.not_received_orders_count}
    </h4>
{/if}
{if !empty($ns.searchText)}
    Searched text corresponding not received to recipient count:  {$ns.searchedItemPuposedCount} ({$ns.searchedItemCountThatHasTrackingNumber})
{/if}

