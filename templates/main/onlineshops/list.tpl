<div class="container partner--list--container">
    <h1 class="main_title">Recipients</h1>

    <div class="main-table">
        <table>
            <tr>
                <th>Id</th>
                <th>Email</th>
            </tr>

            {foreach from=$ns.rows item=row}
                <tr class="table-row">
                    <td >{$row->getEmail()}</td>
                </tr>
            {/foreach}
        </table>
    </div>
</div>