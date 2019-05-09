<div class="container partner--list--container">
    <h1 class="main_title">Vanilla Cards Report</h1>
    {if $ns.user->getType() != 'barney'}
        <div class="filter">
            <form class="filters--form" id="partnerFilters" autocomplete="off" action="{$SITE_PATH}/dyn/main_vanilla/do_add" method="POST">
                <button style="color: #63B4FB; font-size: 18px; background: none; border: 1px solid #63B4FB; padding: 10px">Add</button>
            </form>
        </div>
    {/if}
    {assign payable $ns.totalSuccess*0.7}
    total success: ${$ns.totalSuccess|number_format:2:",":"."} (payable 70%: ${$payable|round:2|number_format:2:",":"."})<br>
    total paid in USD: {$debt}

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
                <th>Note</th>
                <th>Closed</th>
                <th>Updated At</th>
                <th>Created At</th>
                <th>Delete</th>
            </tr>

            {foreach from=$ns.rows item=row}
                <tr {if $row->getDeleted()==1} style="background: #cc0000;"{else}{if $row->getClosed()==1} style="background: lightgray;"{/if}{/if}  class="table-row"  data-type="vanilla" data-id="{$row->getId()}">
                    <td class="table-cell {if $ns.user->getType() != 'barney'}f_editable_cell{/if}" data-field-name="number">{$row->getNumber()}</td>
                    <td class="table-cell {if $ns.user->getType() != 'barney'}f_editable_cell{/if}" data-field-name="month">{$row->getMonth()}</td>
                    <td class="table-cell {if $ns.user->getType() != 'barney'}f_editable_cell{/if}l" data-field-name="year">{$row->getYear()}</td>
                    <td class="table-cell {if $ns.user->getType() != 'barney'}f_editable_cell{/if}" data-field-name="cvv">{$row->getCvv()}</td>
                    <td class="table-cell {if $ns.user->getType() != 'barney'}f_editable_cell{/if}" data-field-name="initial_balance">{$row->getInitialBalance()}</td>
                    <td class="table-cell {if $ns.user->getType() != 'barney'}f_editable_cell{/if}" data-field-name="balance">{$row->getBalance()}</td>
                    <td class="table-cell {if $ns.user->getType() != 'barney'}f_editable_cell{/if}" data-field-name="external_orders_ids">{$row->getExternalOrdersIds()}</td>
                    <td >{$row->getOrdersAmountsText()}</td>
                    <td >{$row->getSucceedAmountsText()}</td>
                    <td class="table-cell {if $ns.user->getType() != 'barney'}f_editable_cell{/if}" data-field-name="note">{$row->getNote()}</td>
                    <td class="icon-cell">
                        {if $ns.user->getType() != 'barney'}
                            <input class="f_closed"
                                   data-id="{$row->getId()}" type="checkbox"
                                   value="1" {if $row->getClosed() == 1}checked{/if}/>
                        {/if}
                    </td>
                    <td class="table-cell {if $ns.user->getType() != 'barney'}f_editable_cell{/if}" data-field-name="updated_at">{$row->getUpdatedAt()}
                        {if $ns.user->getType() != 'barney'}
                            <a href="javascript:void(0);" class="f_update_card" data-id="{$row->getId()}"><img width="20" src="{$SITE_PATH}/img/update.png"/></a>
                        {/if}</td>
                    <td class="table-cell {if $ns.user->getType() != 'barney'}f_editable_cell{/if}" data-field-name="created_at">{$row->getCreatedAt()}</td>
                    <td class="icon-cell">
                        {if $row->getClosed() == 1}
                            Closed<br>
                        {/if}
                        {if $row->getDeleted() == 1}
                            Deleted
                        {else}
                            {if $ns.user->getType() != 'barney'}
                                <a href="{$SITE_PATH}/dyn/main_vanilla/do_delete?id={$row->getId()}">
                                    <span class="button_icon" title="delete">
                                        <i class="fa fa-trash-o"></i>
                                    </span>
                                </a>
                            {/if}
                        {/if}
                    </td>
                </tr>
            {/foreach}
        </table>
    </div>
</div>