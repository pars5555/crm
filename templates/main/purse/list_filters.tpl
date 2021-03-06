<form class="filters--form" id="productFilters" autocomplete="off" action="{$SITE_PATH}/purse/list" method="GET">
    <div class="form-group filters-group">
        {if !empty($ns.user) && $ns.user->getType() == 'root'}
            <div class="filter">
                <label>Recipient</label>
                <select name="rcpt" data-autocomplete="true">
                    <option value="0" {if $ns.selectedFilterRecipientId == 0}selected{/if}>All</option>
                    {foreach from=$ns.recipients item=p}
                        <option value="{$p->getId()}" {if $ns.selectedFilterRecipientId == $p->getId()}selected{/if}>{$p->getFirstName()} {$p->getLastName()}</option>
                    {/foreach}
                </select>
            </div>
        {/if}
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
            <label>Merchant</label>
            <select name="mrch">
                <option value="all" {if $ns.selectedFilterMerchant == 'all'}selected{/if}>All</option>
                {foreach from=$ns.all_merchant_names_list item=merchant_name}
                    <option value="{$merchant_name}" {if $ns.selectedFilterMerchant=== $merchant_name}selected{/if}>{$merchant_name}</option>
                {/foreach}
            </select>

        </div>
        <div class="filter">
            <label>Account</label>
            <select name="acc">
                <option value="all" {if $ns.selectedFilterAccount == 'all'}selected{/if}>All</option>
                {foreach from=$ns.account_names item=account_name}
                    <option value="{$account_name}" {if $ns.selectedFilterAccount === $account_name}selected{/if}>{$account_name}</option>
                {/foreach}
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
            <label>Exact Status</label>
            <select name="estts">
                <option value="all" {if $ns.selectedFilterStatus == 'all'}selected{/if}>All</option>
                <option value="delivered" {if $ns.selectedFilterStatus == 'delivered'}selected{/if}>Delivered</option>
                <option value="canceled" {if $ns.selectedFilterStatus == 'canceled'}selected{/if}>Canceled</option>
                <option value="shipping" {if $ns.selectedFilterStatus == 'shipping'}selected{/if}>Shipping</option>
            </select>
        </div>
             {if !empty($ns.user) && $ns.user->getType() == 'root'}
        <div class="filter">
            <label>Shipping Type</label>
            <select name="sht">
                <option value="all" {if $ns.selectedFilterShippingType == 'all'}selected{/if}>All</option>
                <option value="express" {if $ns.selectedFilterShippingType == 'express'}selected{/if}>Express</option>
                <option value="standard" {if $ns.selectedFilterShippingType == 'standard'}selected{/if}>Standard</option>
            </select>
        </div>
            {/if}
            {if !empty($ns.user) && $ns.user->getType() == 'root'}
        <div class="filter">
            <label>Type (Ex/BTC)</label>
            <select name="tp">
                <option value="all" {if $ns.orderType == 'all'}selected{/if}>All</option>
                <option value="external" {if $ns.orderType == 'external'}selected{/if}>External</option>
                <option value="btc" {if $ns.orderType == 'btc'}selected{/if}>Btc</option>
            </select>
        </div>
            {/if}
        <div class="filter">
            <label>Show Hiddens</label>
            <select name="hddn">
                <option value="all" {if $ns.selectedFilterHidden == 'all'}selected{/if}>All</option>
                <option value="no" {if $ns.selectedFilterHidden == 'no'}selected{/if}>No</option>
            </select>
        </div>
        {if !empty($ns.user) && $ns.user->getType() == 'root'}
            <div class="filter">
                <label>Show Adam only</label>
                <select name="adam">
                    <option value="all" {if $ns.selectedFilterAdam== 'all'}selected{/if}>All</option>
                    <option value="no" {if $ns.selectedFilterAdam == 'no'}selected{/if}>No</option>
                    <option value="yes" {if $ns.selectedFilterAdam == 'yes'}selected{/if}>Yes</option>
                </select>
            </div>
        {/if}
        <div class="filter">
            <label>Problematic</label>
            <input name="pr" type="checkbox" {if $ns.problematic == 1}checked{/if} value="1"/>
        </div>
        <div class="filter">
            <label>last 12 hours changed</label>
            <input name="nc" type="checkbox" {if $ns.new_changed == 1}checked{/if} value="1"/>
        </div>
        {if !empty($ns.user) && $ns.user->getType() == 'root'}
            <div class="filter">
                <label>Admins</label>
                <select name="adm">
                    <option value="all" {if $ns.selectedFilterAdmin == 'all'}selected{/if}>All</option>
                    <option value="lilit" {if $ns.selectedFilterAdmin == 'lilit'}selected{/if}>Lilit</option>
                    <option value="musho" {if $ns.selectedFilterAdmin == 'musho'}selected{/if}>Musho</option>
                </select>
            </div>
        {/if}
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
    {if !empty($ns.user) && $ns.user->getType() == 'root'}
        <div class="filter csv right">
            <a href="javascript:void(0);" class="inline-block" id="export_csv"><img src="{$SITE_PATH}/img/csv.png" width="45"/></a>
        </div>
    {/if}
    <div class="filter">
        <div class="add-new-btn">
            <a id="add_external_order_button" >+</a>
        </div>
    </div>
</form>
{if !empty($ns.user) && $ns.user->getType() == 'root'}
    <button class="button blue small inline f_update_purse" data-account_name='purse_pars'>Pars ({$ns.parsUpdatedDate}) </br><span style="color: #c77405">{$ns.pars_btc_balance|default:'N/A'}</span> ${($ns.btc_rate*$ns.pars_btc_balance)|number_format}</button>
    <button class="button blue small inline f_update_purse" data-account_name='purse_info'>Info ({$ns.infoUpdatedDate}) </br><span style="color: #c77405">{$ns.info_btc_balance|default:'N/A'}</span> ${($ns.btc_rate*$ns.info_btc_balance)|number_format}</button>
    <button class="button blue small inline f_update_purse" data-account_name='purse_checkout'>Checkout ({$ns.checkoutUpdatedDate}) </br><span style="color: #c77405">{$ns.checkout_btc_balance|default:'N/A'}</span> ${($ns.btc_rate*$ns.checkout_btc_balance)|number_format}</button>
    <br/>
    pars: {$ns.pars_btc_address|default:'N/A'}<br/>
    info: {$ns.info_btc_address|default:'N/A'}<br/>
    checkout: {$ns.checkout_btc_address|default:'N/A'}<br/>
{/if}
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
{if !empty($ns.user) && $ns.user->getType() == 'root'}
    <div class="form-group" style="float: right">
        <a id="not_registered_trackings_button" class="button blue small inline">Not Registered Trackings on destination Warehouse</a>
        <a id="hide_by_trackings_button" class="button blue small inline">Hide By Trackings</a>
        <a id="find_trackings_button" class="button blue small inline">Find Trackings</a>
    </div>
{/if}


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

<div id="hide_modalBox" class="modal modal-large">

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
                <select id="local_carrier_name">
                    <option value="globbing">Globbing</option>
                    <option value="onex">Onex</option>
                    <option value="nova">Nova</option>
                </select>
                <textarea id="not_registered_trackings_input" style="width: 100%; height: 100%; " rows="10"></textarea>
                <a class="button blue" id="not_registered_trackings_confirm" href="javascript:void(0);" >Confirm</a>

            </div>
        </div>
    </div>
</div>


<div id="add_external_order_modalBox" class="modal modal-large">
    <div class="modal-container">
        <div class="modal-inner-container" >
            <span class="modal-close">
                <span class="close-icon1"></span>
                <span class="close-icon2"></span>
            </span>
            <h1 class="modal-headline">Product Url</h1>
            <div class="modal-content observers-detail-modal-content form-group" id="observer_details_container">
                <label>Unit Address</label>
                <input class="text" id="external_order_unit_address_input" style="width: 100%" type="text"/>
                <br/>
                corresponding item: 
                <select class="f_purchase_item" id="external_order_product_id" style="max-width: 500px" data-autocomplete="true" data-no-wrap="true">
                    <option value="0">New Item</option>
                    {foreach from=$ns.all_products item=p}
                        <option value="{$p->getId()}">{$p->getName()}</option>
                    {/foreach}
                </select>
                <br/>
                <br/>
                <label>Product Url</label>
                <input class="text"  id="external_order_url_input" style="width: 100%" type="text"/>
                <br/>
                <label>Quantity</label>
                <input class="text" id="external_order_qty_input" type="number"/>
                <br/>
                <label>Price</label>
                <input class="text"  id="external_order_price_input" style="width: 100%" type="number"/>
                <br/>
                <a class="button blue" id="add_external_order_confirm" href="javascript:void(0);" >Confirm</a>

            </div>
        </div>
    </div>
</div>
