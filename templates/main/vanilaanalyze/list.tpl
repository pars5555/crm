<div class="container partner--list--container">
    <h1 class="main_title">Vanilla Cards Analyzer</h1>
            {include file="{ngs cmd=get_template_dir}/main/vanilla/list_filters.tpl"}
    {assign payable $ns.totalSuccess*0.7}
    total success: ${$ns.totalSuccess|number_format:2:",":"."}<br>    
    total confirmed clothing: ${$ns.totalConfirmedClothing|number_format:2:",":"."}<br>    
    total pending clothing: ${$ns.totalPendingClothing|number_format:2:",":"."}<br>    
    total canclled orders that card is still open and pending amount: ${$ns.totalCanclledOrdersPendingBalance|number_format:2:",":"."}<br>    
    pending orders total amount : ${$ns.totalPending|number_format:2:",":"."}<br>    
    total_supplied: ${$ns.total_supplied|number_format:2:",":"."}<br>    
    total available gift cards balance ($10 and less ignores): <span style="font-size: 16px; font-weight: 800">{$ns.total_balance}</span>

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
                <th>Sold to others Total</th>
                <th>Note</th>
                <th>Pending Amounts</th>
                <th>Transactions History</th>
                <th>Closed</th>
                <th>Updated At</th>
                <th>Invalid</th>
                <th>Created At</th>
                <th>Delete</th>
            </tr>

            {foreach from=$ns.rows item=row}
                {assign pams $row->calcPendingAmounts()}
                <tr style="{if $row->getDeleted()==1} background: #cc0000;{else}{if $row->getClosed()==1} background: lightgray; {else}{if $row->getBalanceGrow()==1} background: orange;{else}{if $row->getAttention()==1} background: yellow; {/if}{/if}{/if}{/if}"  class="table-row"  data-type="vanilla" data-id="{$row->getId()}">
                    <td class="table-cell f_editable_cell f_lockable" style="{if $pams[0] <= 1}color: green;font-weight:800{/if}" data-field-name="number">{$row->getNumber()}</td>
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
                    <td class="table-cell f_editable_cell" data-field-name="sold_amount">{$row->getSoldAmount()}</td>
                    <td class="table-cell f_editable_cell" data-field-name="note">{$row->getNote()}</td>
                    
                    <td style="white-space: pre-line" class="table-cell">{if $pams[0] > 0}${$pams[0]} ({foreach from=$pams[1] item=pam}${$pam|number_format:2:".":""}   {/foreach}){/if}</td>
                    <td style="white-space: pre-line" class="table-cell">{$row->getTransactionHistoryText()}</td>
                    <td class="icon-cell">

                        <input class="f_closed"
                               data-id="{$row->getId()}" type="checkbox" value="1" {if $row->getClosed() == 1}checked{/if}/>
                    </td>
                    <td class="table-cell" data-field-name="updated_at">{$row->getUpdatedAt()}</td>
                    <td class="icon-cell">
                        <input class="f_invalid"
                               data-id="{$row->getId()}" type="checkbox" value="1" {if $row->getInvalid() == 1}checked{/if}/>
                    </td>
                    <td class="table-cell " data-field-name="created_at">{$row->getCreatedAt()}</td>
                    <td class="icon-cell">
                        {if $row->getClosed() == 1}
                            Closed<br>
                        {/if}
                        <a href="javascript:void(0);" title="Update card balance as soon as possible" class="f_update_card" data-id="{$row->getId()}"><img width="20" src="{$SITE_PATH}/img/update.png"/></a>
                        
                        
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