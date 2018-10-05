<div class="container sale--open--container">
    <h1 class="main_title">Sale Order Warranty View</h1>

    {if isset($ns.error_message)}
        {include file="{ngs cmd=get_template_dir}/main/message.tpl" type="error" content="{$ns.error_message}"}
    {/if}
    {if isset($ns.success_message)}
        {include file="{ngs cmd=get_template_dir}/main/message.tpl" type="success" content="{$ns.success_message}"}
    {/if}

    {if isset($ns.saleOrder)}
        <div class="main-table">
            <table style="width: auto;" class="margin-auto">
                <tr>
                    <td>id:</td>
                    <td>
                        {$ns.saleOrder->getId()}
                    </td>
                </tr>
                <tr>
                    <td>Date:</td>
                    <td>
                        {$ns.saleOrder->getOrderDate()}
                    </td>
                </tr>
                <tr>
                    <td>Partner:</td>
                    <td>
                        {$ns.saleOrder->getPartnerDto()->getName()}
                    </td>
                </tr>
                <tr>
                    <td>Note:</td>
                    <td>
                        {$ns.saleOrder->getNote()}
                    </td>
                </tr>
                <tr>
                    <td>Paid:</td>
                    <td>
                        {if $ns.saleOrder->getPaid()==1}Yes{else}No{/if}
                    </td>
                </tr>
            </table>
        </div>
            
        <br>
        <h2 class="title">Order Lines</h2>
        <form id="saleOrderLinesForm" method="POST" action="{$SITE_PATH}/dyn/main_sale_warranty/do_save_sale_order_lines_serial_numbers">
            <div class="main-table">
                <table class="table_striped" id="saleOrderLinesContainer">
                    <tr>
                        <th> Product </th>
                        <th> Quantity </th>
                        <th> Unit Price </th>
                        <th> Currency </th>
                    </tr>
                    {if $ns.saleOrder->getSaleOrderLinesDtos()|@count > 0}
                        {assign saleOrderLines $ns.saleOrder->getSaleOrderLinesDtos()}
                        {foreach from=$saleOrderLines item=saleOrderLine}
                            <tr class="saleOrderLine">
                                <td>
                                    {$saleOrderLine->getProductDto()->getName()}
                                </td>
                                <td>
                                    {$saleOrderLine->getQuantity()}
                                </td>
                                <td>
                                    {$saleOrderLine->getUnitPrice()}
                                </td>
                                <td>
                                    {$saleOrderLine->getCurrencyDto()->getTemplateChar()}
                                </td>
                            </tr>
                            <tbody class="saleOrderLineSerialNumbers" id="saleOrderLineSerialNumbersConteiner_{$saleOrderLine->getId()}" pol_id="{$saleOrderLine->getId()}">
                                {if array_key_exists($saleOrderLine->getId(), $polSerialNumbersDtos)}
                                    {foreach from=$polSerialNumbersDtos[$saleOrderLine->getId()] item=saleOrderLineSerialNumber}
                                        <tr>
                                            <td>
                                                <input class="text sn" type="text" value="{$saleOrderLineSerialNumber->getSerialNumber()}"/>
                                            </td>
                                            <td>
                                                <input class="text warranty"  type="number" min="0" step="1" value="{$saleOrderLineSerialNumber->getWarrantyMonths()}"/>
                                            </td>
                                            <td>
                                                <a href="javascript:void(0);" class="button_icon f_delete_polsn" title="delete">
                                                    <i class="fa fa-trash-o"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    {/foreach}
                                {/if}
                            </tbody>
                            <tbody class="snWarrantyNewLines">
                                <tr>
                                    <td>
                                        <input class="text sn" type="text" placeholder="Serial Number"/>
                                    </td>
                                    <td>
                                        <input class="text warranty" type="number" min="0" step="1"  placeholder="Warranty Months"/>
                                    </td>
                                    <td>
                                        <a href="javascript:void(0);" class="button_icon f_add_polsn" title="delete" pol_id="{$saleOrderLine->getId()}">
                                            <i class="fa fa-plus"></i>
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        {/foreach}
                    {/if}
                </table>
            </div>
            <input type="hidden" id="pols_serial_numbers" name="pols_serial_numbers"/>
            <input type="hidden" value="{$ns.saleOrder->getId()}" name="sale_order_id"/>
            <a class="button blue" href="javascript:void(0);" id="submitForm">Save</a>
        </form>

            <table>
                
        <tr id="saleOrderLineSerialNumberRowTemplate" style="display: none">
            <td>
                <input class="text sn" type="text"/>
            </td>
            <td>
                <input class="text warranty" type="number" min="0" step="1"/>
            </td>
            <td>
                <a href="javascript:void(0);" class="button_icon f_delete_polsn" title="delete">
                    <i class="fa fa-trash-o"></i>
                </a>
            </td>
        </tr>
            </table>
    {/if}
</div>
