<form class="filters--form" id="productFilters" autocomplete="off" action="{$SITE_PATH}/purse/list" method="GET">
    {if $ns.pagesCount > 0}
        <div class="form-group">     
            <label>Page</label>
            <select name="pg">
                {for $p=1 to $ns.pagesCount}
                    <option value="{$p}" {if $ns.selectedFilterPage == $p}selected{/if}>{$p}</option>
                {/for}
            </select>
        </div>
    {/if}
    <div class="form-group" style="display: -webkit-box;">     
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
        <label>Search</label>
        <input class="text" style="max-width: 200px;" type="text" name="st" value="{$ns.searchText}"/>
    </div>


</form>
<div class="form-group" style="float: right">
    <a id="upload_button"><img  style="max-width: 100px;max-height: 60px" src="{$SITE_PATH}/img/upload.png"/></a>

    <form id="upload_form" target="is2_upload_target" enctype="multipart/form-data" method="post"
          action="{$SITE_PATH}/dyn/main_purse/do_upload_html" style="width:0; height:0;visibility: none;border:none;">
        <input type="file" id="file_input" name="list_file" style="display:none">
    </form>
    <iframe name="is2_upload_target" style="width:0;height:0;border:0px solid #fff;display: none;"></iframe>
</div>