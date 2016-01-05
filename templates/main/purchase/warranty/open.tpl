<div class="container purchase--open--container">
    <h1 class="main_title">Purchase Order Warranty View</h1>

    {if isset($ns.error_message)}
        {include file="{ngs cmd=get_template_dir}/main/message.tpl" type="error" content="{$ns.error_message}"} 
    {/if}
    {if isset($ns.success_message)}
        {include file="{ngs cmd=get_template_dir}/main/message.tpl" type="success" content="{$ns.success_message}"} 
    {/if}

    {if isset($ns.purchaseOrder)}
        <div class="table_striped table_striped_simple">
            <div class="table-row">
                <span class="table-cell">
                    id :
                </span>
                <span class="table-cell">
                    {$ns.purchaseOrder->getId()}
                </span>
            </div>
            <div class="table-row">
                <span class="table-cell">
                    Date :
                </span>
                <span class="table-cell">
                    {$ns.purchaseOrder->getOrderDate()}
                </span>
            </div>
            <div class="table-row">
                <span class="table-cell">
                    Partner :
                </span>
                <span class="table-cell">
                    {$ns.purchaseOrder->getPartnerDto()->getName()}
                </span>
            </div>
            <div class="table-row">
                <span class="table-cell">
                    Note :
                </span>
                <span class="table-cell">
                    {$ns.purchaseOrder->getNote()}
                </span>
            </div>
            <div class="table-row">
                <span class="table-cell">
                    Paid :
                </span>
                <span class="table-cell">
                    {if $ns.purchaseOrder->getPaid()==1}Yes{else}No{/if}
                </span>
            </div>
        </div>
        <h2 class="title">Order Lines</h2>
        <form id="purchaseOrderLinesForm" method="POST" action="{$SITE_PATH}/dyn/main_purchase_warranty/do_save_purchase_order_lines_serial_numbers">
            <div class="table_striped" id="purchaseOrderLinesContainer">
                <div class="table_header_group">                  
                    <span class="table-cell"> Product </span>
                    <span class="table-cell"> Quantity </span>
                    <span class="table-cell"> Unit Price </span>
                    <span class="table-cell"> Currency </span>
                </div> 
                {if $ns.purchaseOrder->getPurchaseOrderLinesDtos()|@count > 0}
                    {assign purchaseOrderLines $ns.purchaseOrder->getPurchaseOrderLinesDtos()}
                    {foreach from=$purchaseOrderLines item=purchaseOrderLine}
                        <div class="purchaseOrderLine table-row">
                            <div class="table-cell">
                                {$purchaseOrderLine->getProductDto()->getName()}
                            </div>
                            <div class="table-cell">
                                {$purchaseOrderLine->getQuantity()}
                            </div>
                            <div class="table-cell">
                                {$purchaseOrderLine->getUnitPrice()}
                            </div>
                            <div class="table-cell">
                                {$purchaseOrderLine->getCurrencyDto()->getTemplateChar()}
                            </div>
                        </div>
                        <div class="purchaseOrderLineSerialNumbers" id="purchaseOrderLineSerialNumbersConteiner_{$purchaseOrderLine->getId()}" pol_id="{$purchaseOrderLine->getId()}">
                            {if array_key_exists($purchaseOrderLine->getId(), $polSerialNumbersDtos)} 
                                {foreach from=$polSerialNumbersDtos[$purchaseOrderLine->getId()] item=purchaseOrderLineSerialNumber}
                                    <div class="table-row">
                                        <div class="table-cell">           
                                            <input class="text sn" type="text" value="{$purchaseOrderLineSerialNumber->getSerialNumber()}"/>
                                        </div>
                                        <div class="table-cell">           
                                            <input class="text warranty"  type="number" min="0" step="1" value="{$purchaseOrderLineSerialNumber->getWarrantyMonths()}"/>
                                        </div>
                                        <div class="table-cell">           
                                            <a href="javascript:void(0);" class="button_icon f_delete_polsn" title="delete">
                                                <i class="fa fa-trash-o"></i>
                                            </a>
                                        </div>
                                    </div>
                                {/foreach}
                            {/if}
                        </div>
                        <div class="snWarrantyNewLines">
                            <div class="table-row">
                                <div class="table-cell" >           
                                    <input class="text sn" type="text" placeholder="Serial Number"/>
                                </div>
                                <div class="table-cell">           
                                    <input class="text warranty" type="number" min="0" step="1"  placeholder="Warranty Months"/>
                                </div>
                                <div class="table-cell">           
                                    <a href="javascript:void(0);" class="button_icon f_add_polsn" title="delete" pol_id="{$purchaseOrderLine->getId()}">
                                        <i class="fa fa-plus"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    {/foreach}

                {/if}
            </div>
            <input type="hidden" id="pols_serial_numbers" name="pols_serial_numbers"/>            
            <input type="hidden" value="{$ns.purchaseOrder->getId()}" name="purchase_order_id"/>            
            <a class="button blue" href="javascript:void(0);" id="submitForm">Save</a>
        </form>


        <div id="purchaseOrderLineSerialNumberRowTemplate" class="table-row" style="display: none">
            <div class="table-cell">           
                <input class="text sn" type="text"/>
            </div>
            <div class="table-cell">           
                <input class="text warranty" type="number" min="0" step="1"/>
            </div>
            <div class="table-cell">           
                <a href="javascript:void(0);" class="button_icon f_delete_polsn" title="delete">
                    <i class="fa fa-trash-o"></i>
                </a>
            </div>
        </div>
    {/if}
</div>
