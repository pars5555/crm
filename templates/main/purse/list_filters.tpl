<form class="filters--form" id="productFilters" autocomplete="off" action="{$SITE_PATH}/purse/list" method="GET">
    <div class="form-group filters-group">
        <div class="filter">
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
            <label>Account</label>
            <select name="acc">
                <option value="all" {if $ns.selectedFilterAccount == 'all'}selected{/if}>All</option>
                <option value="pars" {if $ns.selectedFilterAccount == 'pars'}selected{/if}>pars5555@yahoo.com</option>
                <option value="info" {if $ns.selectedFilterAccount == 'info'}selected{/if}>info@pcstore.am</option>
                <option value="checkout" {if $ns.selectedFilterAccount == 'checkout'}selected{/if}>checkoutarmenia@gmail.am</option>
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
            <label>Shipping Type</label>
            <select name="sht">
                <option value="all" {if $ns.selectedFilterShippingType == 'all'}selected{/if}>All</option>
                <option value="express" {if $ns.selectedFilterShippingType == 'express'}selected{/if}>Express</option>
                <option value="standard" {if $ns.selectedFilterShippingType == 'standard'}selected{/if}>Standard</option>
            </select>
        </div>
        <div class="filter">
            <label>Show Hiddens</label>
            <select name="hddn">
                <option value="all" {if $ns.selectedFilterHidden == 'all'}selected{/if}>All</option>
                <option value="no" {if $ns.selectedFilterHidden == 'no'}selected{/if}>No</option>
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
</form>
    <button class="button blue small inline f_update_purse" data-account_name='purse_pars'>Pars ({$ns.parsUpdatedDate}) </br><span style="color: #c77405">{$ns.pars_btc_balance|default:'N/A'}</span> ${($ns.btc_rate*$ns.pars_btc_balance)|number_format}</button>
<button class="button blue small inline f_update_purse" data-account_name='purse_info'>Info ({$ns.infoUpdatedDate}) </br><span style="color: #c77405">{$ns.info_btc_balance|default:'N/A'}</span> ${($ns.btc_rate*$ns.info_btc_balance)|number_format}</button>
<button class="button blue small inline f_update_purse" data-account_name='purse_checkout'>Checkout ({$ns.checkoutUpdatedDate}) </br><span style="color: #c77405">{$ns.checkout_btc_balance|default:'N/A'}</span> ${($ns.btc_rate*$ns.checkout_btc_balance)|number_format}</button>
<br/>
pars: {$ns.pars_btc_address|default:'N/A'}<br/>
info: {$ns.info_btc_address|default:'N/A'}<br/>
checkout: {$ns.checkout_btc_address|default:'N/A'}<br/>
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
<div class="form-group" style="float: right">
    <a id="not_registered_trackings_button" class="button blue small inline">Not Registered Trackings on destination Warehouse</a>
    <a id="hide_by_trackings_button" class="button blue small inline">Hide By Trackings</a>
    <a id="find_trackings_button" class="button blue small inline">Find Trackings</a>
</div>


<div id="trackings_modalBox" class="modal modal-large">
    <div class="modal-container">
        <div class="modal-inner-container" >
            <span class="modal-close">
                <span class="close-icon1"></span>
                <span class="close-icon2"></span>
            </span>
            <h1 class="modal-headline">Insert Trackings</h1>
            <div class="modal-content observers-detail-modal-content" id="observer_details_container">
                <textarea id="tracking_numbers_input" style="width: 100%; height: 100%; " rows="10"></textarea>
                <a class="button blue" id="export_search" href="javascript:void(0);" target="_blank">Export Search Result</a>

            </div>
        </div>
    </div>
</div>

<div id="hide_by_trackings_modalBox" class="modal modal-large">
    <div class="modal-container">
        <div class="modal-inner-container" >
            <span class="modal-close">
                <span class="close-icon1"></span>
                <span class="close-icon2"></span>
            </span>
            <h1 class="modal-headline">Insert Trackings</h1>
            <div class="modal-content observers-detail-modal-content" id="observer_details_container">
                <textarea id="hide_by_trackings_input" style="width: 100%; height: 100%; " rows="10"></textarea>
                <a class="button blue" id="hide_by_trackings_confirm" href="javascript:void(0);" >Confirm</a>

            </div>
        </div>
    </div>
</div>

<div id="not_registered_trackings_modalBox" class="modal modal-large">
    <div class="modal-container">
        <div class="modal-inner-container" >
            <span class="modal-close">
                <span class="close-icon1"></span>
                <span class="close-icon2"></span>
            </span>
            <h1 class="modal-headline">Insert All Registered Trackings in warehouses</h1>
            <div class="modal-content observers-detail-modal-content" id="observer_details_container">
                <textarea id="not_registered_trackings_input" style="width: 100%; height: 100%; " rows="10"></textarea>
                <a class="button blue" id="not_registered_trackings_confirm" href="javascript:void(0);" >Confirm</a>

            </div>
        </div>
    </div>
</div>
