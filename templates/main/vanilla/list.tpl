<div class="container partner--list--container">
    <h1 class="main_title">Vanilla Cards Report</h1>
    <div class="filter">
        <form class="filters--form" id="partnerFilters" autocomplete="off" action="{$SITE_PATH}/dyn/main_vanilla/do_add" method="POST">
            <button style="color: #63B4FB; font-size: 18px; background: none; border: 1px solid #63B4FB; padding: 10px">Add</button>
        
        </form>
            <label for="lock_checkbox">Lock edittable fields</label>
            <input id='lock_checkbox' type="checkbox" value="1" checked=""/>
    </div>
            {include file="{ngs cmd=get_template_dir}/main/vanilla/list_filters.tpl"}
    {assign payable $ns.totalSuccess*0.7}
    total success: ${$ns.totalSuccess|number_format:2:",":"."} (payable 70%: ${$payable|round:2|number_format:2:",":"."})<br>
    total paid in USD: {$ns.debt}

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
                <th>order amounts</th>
                <th>Succeed amounts</th>
                <th>Attention</th>
                <th>Note</th>
                <th>Transactions History</th>
                <th>Closed</th>
                <th>Updated At</th>
                <th>Balance Grow</th>
                <th>Created At</th>
                <th>Delete</th>
            </tr>

            {foreach from=$ns.rows item=row}
                <tr {if $row->getDeleted()==1} style="background: #cc0000;"{else}{if $row->getClosed()==1} style="background: lightgray;"{else}{if $row->getBalanceGrow()==1} style="background: orange;"{else}{if $row->getAttention()==1} style="background: yellow;"{/if}{/if}{/if}{/if}  class="table-row"  data-type="vanilla" data-id="{$row->getId()}">
                    <td class="table-cell f_editable_cell f_lockable" data-field-name="number">{$row->getNumber()}</td>
                    <td class="table-cell f_editable_cell f_lockable" data-field-name="month">{$row->getMonth()}</td>
                    <td class="table-cell f_editable_cell f_lockable" data-field-name="year">{$row->getYear()}</td>
                    <td class="table-cell f_editable_cell f_lockable" data-field-name="cvv">{$row->getCvv()}</td>
                    <td class="table-cell f_editable_cell f_lockable" data-field-name="initial_balance">{$row->getInitialBalance()}</td>
                    <td class="table-cell f_editable_cell" data-field-name="balance">{$row->getBalance()}</td>
                    <td class="table-cell f_editable_cell" data-field-name="external_orders_ids">{$row->getExternalOrdersIds()}</td>
                    <td >{$row->getOrdersAmountsText()}</td>
                    <td >{$row->getSucceedAmountsText()}</td>
                    <td class="icon-cell">

                        <input class="f_attention"
                               data-id="{$row->getId()}" type="checkbox" value="1" {if $row->getAttention() == 1}checked{/if}/>
                    </td>
                    <td class="table-cell f_editable_cell" data-field-name="note">{$row->getNote()}</td>
                    <td class="table-cell f_editable_cell" data-field-name="transaction_history">{$row->getTransactionHistory()}</td>
                    <td class="icon-cell">

                        <input class="f_closed"
                               data-id="{$row->getId()}" type="checkbox" value="1" {if $row->getClosed() == 1}checked{/if}/>
                    </td>
                    <td class="table-cell" data-field-name="updated_at">{$row->getUpdatedAt()}

                        <a href="javascript:void(0);" class="f_update_card" data-id="{$row->getId()}"><img width="20" src="{$SITE_PATH}/img/update.png"/></a>
                    </td>
                    <td class="icon-cell">
                        <input class="f_balance_grow"
                               data-id="{$row->getId()}" type="checkbox" value="1" {if $row->getBalanceGrow() == 1}checked{/if}/>
                    </td>
                    <td class="table-cell " data-field-name="created_at">{$row->getCreatedAt()}</td>
                    <td class="icon-cell">
                        {if $row->getClosed() == 1}
                            Closed<br>
                        {/if}
                        {if $row->getDeleted() == 1}
                            Deleted
                        {else}

                            <a href="{$SITE_PATH}/dyn/main_vanilla/do_delete?id={$row->getId()}">
                                <span class="button_icon" title="delete">
                                    <i class="fa fa-trash-o"></i>
                                </span>
                            </a>

                        {/if}
                    </td>
                </tr>
            {/foreach}
        </table>
    </div>
</div>