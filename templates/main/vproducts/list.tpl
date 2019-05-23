<div class="container partner--list--container">
    <h1 class="main_title">CC</h1>
    <div class="filter">
        <form class="filters--form" id="partnerFilters" autocomplete="off" action="{$SITE_PATH}/dyn/main_ccards/do_add" method="POST">
            <button style="color: #63B4FB; font-size: 18px; background: none; border: 1px solid #63B4FB; padding: 10px">Add</button>
        </form>
    </div>
    <div class="main-table">
        <table>
            <tr>
                <th>Name</th>
                <th>Url</th>
                <th>Price</th>
                <th>Note</th>
                <th>Actions</th>
            </tr>

            {foreach from=$ns.rows item=row}
                <tr class="table-row"  data-type="cc" data-id="{$row->getId()}">
                    <td class="table-cell f_editable_cell" data-field-name="name">{$row->getName()}</td>
                    <td class="table-cell f_editable_cell" data-field-name="URL">{$row->getUrl()}</td>
                    <td class="table-cell f_editable_cell" data-field-name="Price">{$row->getMonth()}</td>
                    <td class="table-cell f_editable_cell" data-field-name="note" data-type="richtext" style="min-width: 100px">{$row->getNote()}</td>
                    <td >
                        <a href="javascript:void(0);" class="f_delete">
                            <span class="button_icon" title="delete">
                                <i class="fa fa-trash-o"></i>
                            </span>
                        </a>
                    </td>
                </tr>
            {/foreach}
        </table>
    </div>
</div>