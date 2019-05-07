<div class="container partner--list--container">
    <h1 class="main_title">Recipients</h1>

    <div class="main-table">
        <table>
            <tr>
                <th>Index</th>
                <th>Name</th>
                <th>Login</th>
                <th>Password</th>
                <th>Closed</th>
                <th>Url</th>
            </tr>

            {foreach from=$ns.rows item=row}
                <tr class="table-row"  data-type="online_shop" data-id="{$row->getId()}">
                    <td class="table-cell f_editable_cell" data-field-name="index">{$row->getIndex()}</td>
                    <td>{$row->getName()}</td>
                    <td class="table-cell f_editable_cell" data-field-name="login">{$row->getLogin()}</td>
                    <td class="table-cell f_editable_cell" data-field-name="password">{$row->getPassword()}</td>
                    <td class="table-cell f_editable_cell" data-field-name="closed">{$row->getClosed()}</td>
                    <td class="table-cell f_editable_cell" data-field-name="url">{$row->getUrl()}</td>
                </tr>
            {/foreach}
        </table>
    </div>
</div>