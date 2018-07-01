<form class="filters--form" id="paymentFilters" autocomplete="off" action="{$SITE_PATH}/payment/list" method="GET">
    {if $ns.pagesCount>0}
        <div class="form-group">
            <label>Page</label>
            <select name="pg">
                {for $p=1 to $ns.pagesCount}
                    <option value="{$p}" {if $ns.selectedFilterPage == $p}selected{/if}>{$p}</option>
                {/for}
            </select>
        </div>
    {/if}
    <div class="form-group">
        <label>Partner</label>
        <select name="prt" data-autocomplete="true">
            <option value="0" {if $ns.selectedFilterPartnerId == 0}selected{/if}>All</option>
            {foreach from=$ns.partners item=p}
                <option value="{$p->getId()}" {if $ns.selectedFilterPartnerId == $p->getId()}selected{/if}>{$p->getName()}</option>
            {/foreach}
        </select>
    </div>
    <div class="form-group">
        <label>Currency</label>
        <select name="cur">
            <option value="0" {if $ns.selectedFilterCurrencyId == 0}selected{/if}>All</option>
            {foreach from=$ns.currencies item=c}
                <option value="{$c->getId()}" {if $ns.selectedFilterCurrencyId == $c->getId()}selected{/if}>{$c->getTemplateChar()}</option>
            {/foreach}
        </select>
    </div>
    <div class="form-group">
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
    <div class="form-group" style="float: right">
        <a href="javascript:void(0);" id="export_csv"><img src="/img/csv.png" width="60"/></a>
    </div>
    <div class="form-group">
        <label>Search</label>
        <input class="text" style="max-width: 200px;" type="text" name="st" value="{$ns.searchText}"/>
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
                <label class="label">From </label>
                <input   id='startDateWidget' type="text" value=""/>
                <label class="label">To </label>
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
