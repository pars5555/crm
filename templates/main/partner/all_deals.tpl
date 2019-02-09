<div class="container partner--list--container">
    <h1 class="main_title">{$ns.partner->getName()} All Deals</h1>
    <div class="filter csv">
        <a href="{$SITE_PATH}/dyn/main_partner/do_export_partner_all_deals_csv?id={$ns.partner->getId()}" class="inline-block" id="export_csv"><img src="/img/csv.png" width="45"/></a>
    </div>
    <div class="main-table">
        <table>
            <tr>
                <th> ID </th>
                <th> Type </th>
                <th> Date </th>
                <th> Note </th>
                <th class="icon-cell"> Checked <br/>
                    <input id="f_check_all_checkbox" data-destination_class='f_checked_checkbox' type="checkbox"/>
                </th>
                <th> Amount </th>
                <th> Balance </th>
            </tr>
            {foreach from=$ns.allDeals item=deal}
                <tr {if $deal[1]->getChecked() == 1}style="background: green"{/if}>
                    <td>
                        <a href="{$SITE_PATH}/{$deal[0]}/{$deal[1]->getId()}" target="_blank">
                            <span>{$deal[1]->getId()} </span>
                        </a>
                    </td>
                    <td>
                        <a href="{$SITE_PATH}/{$deal[0]}/{$deal[1]->getId()}" target="_blank">
                            <span>{$deal[0]} </span>
                        </a>
                    </td>
                    <td>
                        <a>
                            {if $deal[0] == 'sale' || $deal[0] == 'purchase'}
                                <span>{$deal[1]->getOrderDate()} </span>
                            {else}
                                <span>{$deal[1]->getDate()} </span>
                            {/if}
                        </a>
                    </td>
                    <td>
                        <a>
                            <span>{$deal[1]->getNote()} </span>
                        </a>
                    </td>
                    <td class="icon-cell">
                        <input data-type="{$deal[0]}" data-id="{$deal[1]->getId()}"
                               class="f_checked_checkbox"  type="checkbox"
                               value="1" {if $deal[1]->getChecked() ==1}checked{/if}/>
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
                                {$deal[1]->getAmount()|number_format:2}
                                {if $currencyDto->getSymbolPosition() == 'right'}
                                    {$currencyDto->getTemplateChar()}
                                {/if}
                            </span>
                            <span> </span>
                        {/if}
                    </td>
                    <td>
                        <a>
                            {foreach from=$deal[1]->getDebt() key=currencyId item=amount}
                                <span style="white-space-collapse: discard;">
                                    {assign currencyDto $ns.currencies[$currencyId]}
                                    {if $currencyDto->getSymbolPosition() == 'left'}
                                        {$currencyDto->getTemplateChar()}
                                    {/if}
                                    {$amount}
                                    {if $currencyDto->getSymbolPosition() == 'right'}
                                        {$currencyDto->getTemplateChar()}
                                    {/if}
                                </span>
                            {/foreach}
                        </a>
                    </td>
                </tr>
            {/foreach}
        </table>
    </div>
</div>