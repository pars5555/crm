<div class="container partner--list--container">
    <h1 class="main_title">Recipients</h1>
    <div class="filter">
        <form class="filters--form" id="partnerFilters" autocomplete="off" action="{$SITE_PATH}/dyn/main_vanilla/do_add" method="POST">
            <button style="color: #63B4FB; font-size: 18px; background: none; border: 1px solid #63B4FB; padding: 10px">Add</button>
        </form>
    </div>

    <div class="main-table">
        <table>
            <tr>
                <th>Number</th>
                <th>Month</th>
                <th>Year</th>
                <th>CVV</th>
                <th>Initial Balance</th>
                <th>Balance</th>
                <th>ExternalOrderIds</th>
                <th>Note</th>
                <th>Closed</th>
                <th>Updated At</th>
                <th>Created At</th>
            </tr>

            {foreach from=$ns.rows item=row}
                <tr {if $row->getClosed()==1} style="background: lightgray;"{/if} class="table-row"  data-type="vanilla" data-id="{$row->getId()}">
                    <td class="table-cell f_editable_cell" data-field-name="number">{$row->getNumber()}</td>
                    <td class="table-cell f_editable_cell" data-field-name="month">{$row->getMonth()}</td>
                    <td class="table-cell f_editable_cell" data-field-name="year">{$row->getYear()}</td>
                    <td class="table-cell f_editable_cell" data-field-name="cvv">{$row->getCvv()}</td>
                    <td class="table-cell f_editable_cell" data-field-name="Initial Balance">{$row->getBalance()}</td>
                    <td class="table-cell f_editable_cell" data-field-name="balance">{$row->getBalance()}</td>
                    <td class="table-cell f_editable_cell" data-field-name="external_orders_ids">{$row->getExternalOrdersIds()}</td>
                    <td class="table-cell f_editable_cell" data-field-name="note">{$row->getNote()}</td>
                    <td class="icon-cell">
                            <input class="f_closed"
                                   data-id="{$row->getId()}" type="checkbox"
                                   value="1" {if $row->getClosed() == 1}checked{/if}/>
                        </td>
                    <td class="table-cell f_editable_cell" data-field-name="updated_at">{$row->getUpdatedAt()}
                        <a href="javascript:void(0);" class="f_update_card" data-id="{$row->getId()}"><img width="20" src="{$SITE_PATH}/img/update.png"/></a></td>
                    <td class="table-cell f_editable_cell" data-field-name="created_at">{$row->getCreatedAt()}</td>
                </tr>
            {/foreach}
        </table>
    </div>
</div>