<div class="container warehouse--container">
    <div class="main-table">
        <table>
            <tr>
                <th> Name </th>
                <th> Value </th>
            </tr>
            {foreach from=$ns.rows item=row}
                <tr data-id="{$row->getId()}" data-type="settings">
                    <td>{$row->getVar()}</td>
                    <td class="f_editable_cell"
                          data-field-name="value"
                          style="word-break: break-all; min-width: 200px;"
                          data-type="settings">{$row->getValue()}</td>
                </tr>
            {/foreach}
        </table>
    </div>
</div>
