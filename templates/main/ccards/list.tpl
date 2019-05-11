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
                <th>Description</th>
                <th>Number</th>
                <th>Month</th>
                <th>Year</th>
                <th>CVV</th>
                <th>Currency</th>
                <th>Cardholder Name</th>
                <th>Phone</th>
                <th>ssid</th>
                <th>arca</th>
                <th>pin</th>
                <th>password</th>
                <th>Billing Address</th>
                <th>Note</th>
                <th>Actions</th>
            </tr>

            {foreach from=$ns.rows item=row}
                <tr   class="table-row"  data-type="cc" data-id="{$row->getId()}">
                    <td class="table-cell f_editable_cell" data-field-name="description">{$row->getDescription()}</td>
                    <td class="table-cell f_editable_cell" data-field-name="number">{$row->getNumber()}</td>
                    <td class="table-cell f_editable_cell" data-field-name="month">{$row->getMonth()}</td>
                    <td class="table-cell f_editable_cell" data-field-name="year">{$row->getYear()}</td>
                    <td class="table-cell f_editable_cell" data-field-name="cvv">{$row->getCvv()}</td>
                    <td class="table-cell f_editable_cell" data-field-name="currency">{$row->getCurrency()}</td>
                    <td class="table-cell f_editable_cell" data-field-name="cardholder_name">{$row->getCardholderName()}</td>
                    <td class="table-cell f_editable_cell" data-field-name="phone">{$row->getPhone()}</td>
                    <td class="table-cell f_editable_cell" data-field-name="ssid">{$row->getSsid()}</td>
                    <td class="table-cell f_editable_cell" data-field-name="arca">{$row->getArca()}</td>
                    <td class="table-cell f_editable_cell" data-field-name="pin">{$row->getPin()}</td>
                    <td class="table-cell f_editable_cell" data-field-name="password">{$row->getPassword()}</td>
                    <td class="table-cell f_editable_cell" data-field-name="billing_address" data-type="richtext" style="min-width: 100px">{$row->getBillingAddress()}</td>
                    <td class="table-cell f_editable_cell" data-field-name="note" data-type="richtext" style="min-width: 100px">{$row->getNote()}</td>
                    <td >
                        <a href="{$SITE_PATH}/dyn/main_ccards/do_delete?id={$row->getId()}">
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