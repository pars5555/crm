<div class="container warehouse--container">
    <div class="table_striped">
        <div class="table_header_group">
            <span class="table-cell"> Name </span>
            <span class="table-cell"> Value </span>
        </div> 
        {foreach from=$ns.rows item=row}
            <div class="table-row" data-id="{$row->getId()}" data-type="settings">
                <span class="table-cell">{$row->getVar()}</span>
                <span class="table-cell f_editable_cell" data-field-name="value" data-type="settings">{$row->getValue()}</span>
            </div>
        {/foreach}
    </div>
</div>
