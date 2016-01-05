<form class="filters--form" id="purchaseOrderFilters" autocomplete="off" action="{$SITE_PATH}/purchase/list" method="GET">
    <div class="form-group">
        <label class="label">Partner</label>
        <select name="prt" data-autocomplete="true">
            <option value="0" {if $ns.selectedFilterPartnerId == 0}selected{/if}>All</option>
            {foreach from=$ns.partners item=p}
                <option value="{$p->getId()}" {if $ns.selectedFilterPartnerId == $p->getId()}selected{/if}>{$p->getName()}</option>
            {/foreach}
        </select>
    </div>
    <div class="form-group">
        <label class="label">Paid</label>
        <select name="paid">
            <option value="-1" {if $ns.selectedFilterPaid === -1}selected{/if}>All</option>
            <option value="1" {if $ns.selectedFilterPaid === 1}selected{/if}>Yes</option>
            <option value="0" {if $ns.selectedFilterPaid === 0}selected{/if}>No</option>
        </select>
    </div>
    {if $ns.pagesCount>0}
        <div class="form-group">
            <label class="label">Page</label>
            <select name="pg">
                {for $p=1 to $ns.pagesCount}
                    <option value="{$p}" {if $ns.selectedFilterPage == $p}selected{/if}>{$p}</option>
                {/for}
            </select>
        </div>
    {/if}
    <div class="form-group">
        <label class="label">Sort by </label>
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
</form>
