<!DOCTYPE html>
<html lang="en">
    <head> 
        {include file="{ngs cmd=get_template_dir}/main/util/header_control.tpl"}
    </head>
    <body>
        <div class="main-container" id="mainContainer">
            <div id="ajaxLoader"></div>

            <header class="header">
                {include file="{ngs cmd=get_template_dir}/main/util/header.tpl"}
            </header>
            <section  id="mainWrapper">
                <div class="content" id="indexRightContent">
                    <div class="container partner--list--container">
                        <h1 class="main_title">Vanilla Cards Report</h1>
                        {assign payable $ns.totalSuccess*0.7}
                        <h1 >total success: ${$ns.totalSuccess|number_format:2:",":"."} (payable 70%: ${$payable|round:2|number_format:2:",":"."})</h1><br>
                        <h1 >total paid in USD: {$ns.debt}</h1>

                        <div class="main-table">
                            <table>
                                <tr>
                                    <th>Number</th>
                                    <th>Month</th>
                                    <th>Year</th>
                                    <th>CVV</th>
                                    <th>Initial Balance</th>
                                    <th>Balance</th>
                                    <th>order amounts</th>
                                    <th>Succeed amounts</th>
                                    <th>Note</th>
                                    <th>Closed</th>
                                    <th>Updated At</th>
                                    <th>Created At</th>
                                    <th>Status</th>
                                </tr>

                                {foreach from=$ns.rows item=row}
                                    <tr {if $row->getDeleted()==1} style="background: #cc0000;"{else}{if $row->getClosed()==1} style="background: lightgray;"{/if}{/if}  class="table-row"  data-type="vanilla" data-id="{$row->getId()}">
                                        <td class="table-cell " data-field-name="number">{$row->getNumber()|truncate:13:'xxx'}</td>
                                        <td class="table-cell " data-field-name="month">{$row->getMonth()}</td>
                                        <td class="table-cell " data-field-name="year">{$row->getYear()}</td>
                                        <td class="table-cell " data-field-name="cvv">xxx</td>
                                        <td class="table-cell " data-field-name="initial_balance">{$row->getInitialBalance()}</td>
                                        <td class="table-cell " data-field-name="balance">{$row->getBalance()}</td>
                                        <td >{$row->getOrdersAmountsText()}</td>
                                        <td >{$row->getSucceedAmountsText()}</td>
                                        <td class="table-cell " data-field-name="note">{$row->getNote()}</td>
                                        <td class="icon-cell">
                                            {if $row->getClosed() == 1}yes{else}no{/if}
                                        </td>
                                        <td class="table-cell" data-field-name="updated_at">{$row->getUpdatedAt()}</td>
                                        <td class="table-cell " data-field-name="created_at">{$row->getCreatedAt()}</td>
                                        <td class="icon-cell">
                                            {if $row->getClosed() == 1}
                                                Closed<br>
                                            {/if}
                                        </td>
                                    </tr>
                                {/foreach}
                            </table>
                        </div>
                    </div>


                </div>


            </section>

        </div>


    </body>
</html>