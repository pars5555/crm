<div class="container partner--list--container">
    <h1 class="main_title">Vanilla Cards Report</h1>
    <div class="filter">
        {if $ns.selectedFilterPartnerId > 0}
            <form class="filters--form" id="partnerFilters" autocomplete="off" action="{$SITE_PATH}/dyn/main_giftcards/do_add" method="POST">
                <input type="hidden" value="{$ns.selectedFilterPartnerId}" name="partner_id"/>
                <button type="submit" style="color: #63B4FB; font-size: 18px; background: none; border: 1px solid #63B4FB; padding: 10px">Add</button>
            </form>
        {/if}
    </div>
    {include file="{ngs cmd=get_template_dir}/main/giftcards/list_filters.tpl"}
    
    {if $ns.selectedFilterPartnerId > 0}
        total supplied: {$ns.total}<br>
        total discounted amount: {$ns.total_discounted}<br>
        debt: {$ns.debt}<br>
    {/if}

    <div class="main-table">
        <table>
            <tr>
                <th>Id</th>
                <th>Code</th>
                <th>Balance</th>
                <th>Discount</th>
                <th>Dscounted Amount</th>
                <th>Account</th>
                <th>ExternalOrderIds</th>
                <th>Attention</th>
                <th>Note</th>
                <th>Created At</th>
            </tr>

            {foreach from=$ns.rows item=row}
                <tr {if $row->getAttention()==1} style="background: yellow;"{/if}  class="table-row"  data-type="giftcards" data-id="{$row->getId()}">
                    <td>
                        <a href="{$SITE_PATH}/giftcards/{$row->getId()}" class="link" target="_blank">{$row->getId()}</a><br/>
                        {if isset($ns.attachments[$row->getId()])}
                            <img src="{$SITE_PATH}/img/attachment.png" width="32"/>
                        {/if}
                    </td>
                    <td class="table-cell f_editable_cell" data-field-name="code">{$row->getCode()}</td>
                    <td class="table-cell f_editable_cell" data-field-name="amount">{$row->getAmount()}</td>
                    <td class="table-cell f_editable_cell" data-field-name="discount_percent">{$row->getDiscountPercent()}</td>
                    <td class="table-cell">{$row->getAmountDiscounted()}</td>
                    <td class="f_selectable_cell" data-value="{$row->getAccountName()}" data-template-select-id="account_name_list" data-field-name="account_name"> {$row->getAccountName()}</td>
                    <td class="table-cell f_editable_cell" data-field-name="external_order_ids">{$row->getExternalOrderIds()}</td>
                    <td class="icon-cell">
                        <input class="f_attention"
                               data-id="{$row->getId()}" type="checkbox" value="1" {if $row->getAttention() == 1}checked{/if}/>
                    </td>
                    <td class="table-cell f_editable_cell" data-field-name="note">{$row->getNote()}</td>
                    <td class="table-cell " data-field-name="created_at">{$row->getCreatedAt()}</td>
                </tr>
            {/foreach}
        </table>
    </div>
</div>
<select id='account_name_list' class="hidden" style="width: 120px" >
    <option value="ebay">ebay</option>
    <option value="amazon">amazon</option>
</select>