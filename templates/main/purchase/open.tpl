<div class="container purchase--open--container">
    <h1 class="main_title">Purchase Order View</h1>
    
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
        </div>
        {if $ns.purchaseOrder->getCancelled() == 0}
            <form action="{$SITE_PATH}/dyn/main_purchase/do_cancel_purchase_order">
                <input type="hidden" name="id" id="purchase_order_id" value="{$ns.purchaseOrder->getId()}"/>
                <div class="form-group">
                    <label class="label">Note</label>
                    <textarea class="text" name="note"></textarea>
                </div>
                <a class="button blue" id="cancelPurchaseOrderButton" href="javascript:void(0);">Cancel</a>
            </form>
        {else}
            <a class="table-cell"  href="{$SITE_PATH}/dyn/main_purchase/do_restore_purchase_order?id={$ns.purchaseOrder->getId()}">
                <span class="button blue">Restore</span>
            </a>
        {/if}
         <a class="button blue" href="{$SITE_PATH}/dyn/main_payment/do_redirect?partnerId={$ns.purchaseOrder->getPartnerId()}&note=Payment for Purchase Order No-{$ns.purchaseOrder->getId()}">Pay</a>
         <a class="button blue" href="{$SITE_PATH}/purchase/warranty/{$ns.purchaseOrder->getId()}">Warranty</a>
	
        <div class="checkbox_container">
            <div class="checkbox f_checkbox">
                <input type="checkbox" id="paidCheckbox" {if $ns.purchaseOrder->getPaid()==1}checked{/if}/>
            </div>
            <label class="checkbox_label f_checkbox_label label">Paid</label>
        </div> 

        <form id="purchaseOrderLinesForm" method="POST" action="{$SITE_PATH}/dyn/main_purchase/do_save_purchase_order_lines">
            <h2 class="title">Order Lines</h2>
            <div class="table_striped" id="purchaseOrderLinesContainer">
                <div class="table_header_group">                  
                    <span class="table-cell"> Product </span>
                    <span class="table-cell"> Quantity </span>
                    <span class="table-cell"> Unit Price </span>
                    <span class="table-cell"> Currency </span>
                    <span class="table-cell"> Delete </span>
                </div> 
                {if $ns.purchaseOrder->getPurchaseOrderLinesDtos()|@count > 0}
                    {assign purchaseOrderLines $ns.purchaseOrder->getPurchaseOrderLinesDtos()}
                    {foreach from=$purchaseOrderLines item=purchaseOrderLine}
                        <div class="purchaseOrderLine table-row" line_id="{$purchaseOrderLine->getId()}">
                            <div class="table-cell">
                                <select class="purchaseOrderLinesSelectProduct" data-autocomplete="true" data-no-wrap="true">
                                    {foreach from=$ns.products item=p}
                                        <option value="{$p->getId()}" {if $p->getId() == $purchaseOrderLine->getProductId()}selected{/if}>{$p->getName()}</option>
                                    {/foreach}
                                </select>
                            </div>
                            <div class="table-cell">
                                <input type="number" step="0.1"  min="0.1" class="purchaseOrderLinesSelectQuantity text" value="{$purchaseOrderLine->getQuantity()}"/>
                            </div>
                            <div class="table-cell">
                                <input type="number" step="0.01" min="0.01" class="purchaseOrderLinesSelectUnitPrice text" value="{$purchaseOrderLine->getUnitPrice()}"/>
                            </div>
                            <div class="table-cell">
                                <select class="purchaseOrderLinesSelectCurrency">               
                                    {foreach from=$ns.currencies item=c}
                                        <option value="{$c->getId()}"  {if $c->getId() == $purchaseOrderLine->getCurrencyId()}selected{/if}>
                                            {$c->getName()} ({$c->getIso()} {$c->getTemplateChar()})
                                        </option>
                                    {/foreach}
                                </select>
                            </div>
                            <div class="table-cell">
                                <a class="button_icon removePurchaseOrderLine" title="delete">
                                    <i class="fa fa-trash-o"></i>
                                </a>
                            </div>
                            <input type="hidden" name="lines[]"/>
                        </div>
                    {/foreach}
                {/if}
            </div>
            <input type="hidden" value="{$ns.purchaseOrder->getId()}" name="purchase_order_id"/>
        </form>

        {*                           ADD NEW PURCHASE OREDER LINE                                 *}
        <div class="table_striped add_new_purchase_order_line">
            <div class="table-row">
                <div class="table-cell">
                    <select id="purchaseOrderLineProductId" style="max-width: 500px" data-autocomplete="true" data-no-wrap="true">                       
                        <option value="0">Select...</option>
                        {foreach from=$ns.products item=p}
                            <option value="{$p->getId()}">{$p->getName()}</option>
                        {/foreach}
                    </select>
                </div>
                <div class="table-cell">
                    <input class="text" type="number" step="0.1"  min="0.1" id="purchaseOrderLineQuantity"/>
                </div>
                <div class="table-cell">
                    <input class="text" type="number" step="0.01"  min="0.01" id="purchaseOrderLineUnitPrice"/>
                </div>
                <div class="table-cell">
                    <select id="purchaseOrderLineCurrencyId">               
                        <option value="0">Select...</option>
                        {foreach from=$ns.currencies item=c}
                            <option value="{$c->getId()}">{$c->getName()} ({$c->getIso()} {$c->getTemplateChar()})</option>
                        {/foreach}
                    </select>
                </div>
                <div class="table-cell">
                    <a class="button_icon" href="javascript:void(0);" id="addPurchaseOrderLineButton" title="Add">
                        <i class="fa fa-plus-circle"></i>
                    </a>
                </div>
            </div>
            <div>
                <span id="purchaseOrderLineErrorMessage" style="color:red"></span>
            </div>
        </div>

        <a class="button blue" href="javascript:void(0);" id="submitForm">Save</a>

    {/if}
</div>


<div class="table-row" id="purchaseOrderLineTemplate" style="display:none">
    <div class="table-cell">
        <select class="purchaseOrderLinesSelectProduct"  data-no-wrap="true">
            {foreach from=$ns.products item=p}
                <option value="{$p->getId()}">{$p->getName()}</option>
            {/foreach}
        </select>
    </div>
    <div class="table-cell">
        <input  type="number" step="0.1"  min="0.1" class="purchaseOrderLinesSelectQuantity text"/>
    </div>
    <div class="table-cell">
        <input  type="number" step="0.01" min="0.01" class="purchaseOrderLinesSelectUnitPrice text"/>
    </div>
    <div class="table-cell">
        <select class="purchaseOrderLinesSelectCurrency">               
            {foreach from=$ns.currencies item=c}
                <option value="{$c->getId()}">{$c->getName()} ({$c->getIso()} {$c->getTemplateChar()})</option>
            {/foreach}
        </select>
    </div>
    <div class="table-cell">
        <a class="button_icon removePurchaseOrderLine" title="delete">
            <i class="fa fa-trash-o"></i>
        </a>
    </div>
    <input type="hidden" name="lines[]"/>
</div>
