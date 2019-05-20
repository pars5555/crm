<div class="container partner--list--container">
    <h1 class="main_title">Recipients</h1>

    <div class="main-table">
        <table>
            <tr>
                <th>Id</th>
                <th>Email</th>
                <th>Name</th>
                <th>Referrer Id</th>
                <th>Referrer Email</th>
            </tr>

            {foreach from=$ns.rows item=row}
                <tr class="table-row">
                    <td>{$row->id}</td>
                    <td>{$row->email}</td>
                    <td>{$row->first_name} {$row->last_name}</td>
                    <td>{$row->referrer_id}</td>
                    <td>{$row->referrer_id}</td>
                </tr>
            {/foreach}
        </table>
    </div>
</div>