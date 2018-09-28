<div class="container all--list--container">
    <h1 class="main_title">All Deals</h1>
    <div class="form-group">    
        <label class="label">From </label>
        {html_select_date prefix='startDate' start_year=2010 end_year=2020 field_order=YMD time=$ns.startDate}
        <label class="label">To </label>
        {html_select_date prefix='endDate' start_year=2010 end_year=2020 field_order=YMD time=$ns.endDate}
    </div>

    <div class="main-table">
        <table>
            <tr>
                <th>Id</th>
                <th>Type</th>
                <th>Partner</th>
                <th>Date</th>
                <th>Note</th>
                <th class="icon-cell">
                    <input id="f_check_all_checkbox"
                           data-destination_class='f_checked_checkbox'
                           style="width: 18px; height: 18px;"
                           type="checkbox"/>
                </th>
                <th>Amount</th>
            </tr>

            {foreach from=$ns.allDeals item=deal}
                <tr data-type="{$deal[0]}" data-id="{$deal[1]->getId()}" {if $deal[1]->getChecked() == 1}style="background: green"{/if}>
                    <td class="link-cell id">
                        <a href="{$SITE_PATH}/{$deal[0]}/{$deal[1]->getId()}" target="_blank">
                            <span>{$deal[1]->getId()}</span>
                        </a>
                    </td>
                    <td class="link-cell">
                        <a href="{$SITE_PATH}/{$deal[0]}/{$deal[1]->getId()}" target="_blank">
                            <span>{$deal[0]}</span>
                        </a>
                    </td>
                    <td class="link-cell">
                        <a href="{$SITE_PATH}/partner/{$deal[1]->getPartnerDto()->getId()}" target="_blank">
                            <span>{$deal[1]->getPartnerDto()->getName()}</span>
                        </a>
                    </td>
                    <td class="link-cell">
                        <a class="table-cell">
                            {if $deal[0] == 'sale' || $deal[0] == 'purchase'}
                                <span>{$deal[1]->getOrderDate()}</span>
                            {else}
                                <span>{$deal[1]->getDate()}</span>
                            {/if}
                        </a>
                    </td>
                    <td class="link-cell">
                        <a data-field-name="note">
                            <span>{$deal[1]->getNote()} </span>
                        </a>
                    </td>
                    <td class="icon-cell">
                        <input data-type="{$deal[0]}" data-id="{$deal[1]->getId()}" class="f_checked_checkbox" type="checkbox" value="1" {if $deal[1]->getChecked() ==1}checked{/if}/>
                    </td>
                    <td>
                        {if $deal[0] == 'sale' || $deal[0] == 'purchase'}
                            {assign totalAmount $deal[1]->getTotalAmount()}
                            {foreach from=$totalAmount key=currencyId item=amount}
                                <span class="price">
                                    {assign currencyDto $ns.currencies[$currencyId]}
                                    {if $currencyDto->getSymbolPosition() == 'left'}
                                        {$currencyDto->getTemplateChar()}
                                    {/if}
                                    {$amount|number_format:2}
                                    {if $currencyDto->getSymbolPosition() == 'right'}
                                        {$currencyDto->getTemplateChar()}
                                    {/if}
                                </span>
                            {/foreach}
                        {else}
                            <span class="price">
                                {assign currencyDto $ns.currencies[$deal[1]->getCurrencyId()]}
                                {if $currencyDto->getSymbolPosition() == 'left'}
                                    {$currencyDto->getTemplateChar()}
                                {/if}
                                {if $deal[0] == 'billing'}
                                    {(-$deal[1]->getAmount())|number_format:2}
                                {else}
                                    {$deal[1]->getAmount()|number_format:2}
                                {/if}
                                {if $currencyDto->getSymbolPosition() == 'right'}
                                    {$currencyDto->getTemplateChar()}
                                {/if}
                            </span>
                        {/if}
                    </td>
                </tr>
            {/foreach}
        </table>
    </div>
</div>