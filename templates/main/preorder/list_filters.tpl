<form class="filters--form" id="preorderFilters" autocomplete="off" action="{$SITE_PATH}/preorder/list" method="GET">
    <div class="form-group filters-group">
        <div class="filter">
            <label>Partner</label>
            <select name="prt" data-autocomplete="true">
                <option value="0" {if $ns.selectedFilterPartnerId == 0}selected{/if}>All</option>
                {foreach from=$ns.partners item=p}
                    <option value="{$p->getId()}" {if $ns.selectedFilterPartnerId == $p->getId()}selected{/if}>{$p->getName()}</option>
                {/foreach}
            </select>
        </div>
        <div class="filter">
            <label>Paid</label>
            <select name="paid">
                <option value="-1" {if $ns.selectedFilterPaid === -1}selected{/if}>All</option>
                <option value="1" {if $ns.selectedFilterPaid === 1}selected{/if}>Yes</option>
                <option value="0" {if $ns.selectedFilterPaid === 0}selected{/if}>No</option>
            </select>
        </div>
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
        <div class="filter csv">
            <a href="javascript:void(0);" id="export_csv"><img src="/img/csv.png" width="60"/></a>
        </div>
        <div class="filter">
            <div class="add-new-btn">
                <a href="{$SITE_PATH}/preorder/create">
                    +
                </a>
            </div>
        </div>
    </div>
</form>

<div id="export_modalBox" class="modal modal-large">
    <div class="modal-container">
        <div class="modal-inner-container" >
            <span class="modal-close">
                <span class="close-icon1"></span>
                <span class="close-icon2"></span>
            </span>
            <h1 class="modal-headline">Export</h1>
            <div class="modal-content observers-detail-modal-content" id="observer_details_container">
                <label>From </label>
                <input   id='startDateWidget' type="text" value=""/>
                <label>To </label>
                <input id='endDateWidget' type="text" value=""/>
                <div class="form-group">
                    <label>Partner</label>
                    <select class="f_partner_id" data-autocomplete="true">
                        <option value="0" >All</option>
                        {foreach from=$ns.partners item=p}
                            <option value="{$p->getId()}">{$p->getName()}</option>
                        {/foreach}
                    </select>
                </div>
                <input type="hidden" id="exportStartDate"/>
                <input type="hidden" id="exportEndDate"/>
                <a class="button blue f_export" href="javascript:void(0);" target="_blank">Export</a>

            </div>
        </div>
    </div>
</div>



