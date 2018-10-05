<div class="container purchase--open--container">
    <h1 class="main_title">Purchase Order View</h1>

    {if isset($ns.error_message)}
        {include file="{ngs cmd=get_template_dir}/main/message.tpl" type="error" content="{$ns.error_message}"}
    {/if}
    {if isset($ns.success_message)}
        {include file="{ngs cmd=get_template_dir}/main/message.tpl" type="success" content="{$ns.success_message}"}
    {/if}

    {if isset($ns.purchaseOrder)}
        <div class="main-table">
            <table style="width: auto;" class="margin-auto">
                <tr>
                    <td>id:</td>
                    <td>
                        {$ns.purchaseOrder->getId()}
                    </td>
                </tr>
                <tr>
                    <td>Date:</td>
                    <td>
                        {$ns.purchaseOrder->getOrderDate()}
                    </td>
                </tr>
                <tr>
                    <td>Partner:</td>
                    <td>
                        {$ns.purchaseOrder->getPartnerDto()->getName()}
                    </td>
                </tr>
                <tr>
                    <td>Note:</td>
                    <td>
                        {$ns.purchaseOrder->getNote()}
                    </td>
                </tr>
            </table>
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

            <div class="main-table">
                <table id="purchaseOrderLinesContainer">
                    <tr class="table_header_group">
                        <th> Product </th>
                        <th> Quantity </th>
                        <th> Unit Price </th>
                        <th> Currency </th>
                        <th> Delete </th>
                    </tr>
                    {if $ns.purchaseOrder->getPurchaseOrderLinesDtos()|@count > 0}
                        {assign purchaseOrderLines $ns.purchaseOrder->getPurchaseOrderLinesDtos()}
                        {foreach from=$purchaseOrderLines item=purchaseOrderLine}
                            <tr class="purchaseOrderLine" line_id="{$purchaseOrderLine->getId()}">
                                <td>
                                    {foreach from=$ns.products item=p}
                                        {if $p->getId() == $purchaseOrderLine->getProductId()}
                                            {assign productName $p->getName()}
                                        {/if}
                                    {/foreach}
                                    <select class="purchaseOrderLinesSelectProduct" title="{$productName}" style="max-width: 500px" data-autocomplete="true" data-no-wrap="true">
                                        {foreach from=$ns.products item=p}
                                            <option value="{$p->getId()}" {if $p->getId() == $purchaseOrderLine->getProductId()}selected{/if}>{$p->getName()}</option>
                                        {/foreach}
                                    </select>
                                </td>
                                <td>
                                    <input type="number" step="0.1"  min="0.1" class="purchaseOrderLinesSelectQuantity text" value="{$purchaseOrderLine->getQuantity()}"/>
                                </td>
                                <td>
                                    <input type="number" step="0.01" min="0.01" class="purchaseOrderLinesSelectUnitPrice text" value="{$purchaseOrderLine->getUnitPrice()}"/>
                                </td>
                                <td>
                                    <select class="purchaseOrderLinesSelectCurrency">
                                        {foreach from=$ns.currencies item=c}
                                            <option value="{$c->getId()}"  {if $c->getId() == $purchaseOrderLine->getCurrencyId()}selected{/if}>
                                                {$c->getName()} ({$c->getIso()} {$c->getTemplateChar()})
                                            </option>
                                        {/foreach}
                                    </select>
                                </td>
                                <td>
                                    <a class="button_icon removePurchaseOrderLine" title="delete">
                                        <i class="fa fa-trash-o"></i>
                                    </a>
                                    <input type="hidden" name="lines[]"/>
                                </td>
                            </tr>
                        {/foreach}
                    {/if}
                </table>
            </div>
            <input type="hidden" value="{$ns.purchaseOrder->getId()}" name="purchase_order_id"/>
        </form>

        {*                           ADD NEW PURCHASE OREDER LINE                                 *}
        <div class="main-table">
            <table class="add_new_purchase_order_line">
                <tr>
                    <td>
                        <select id="purchaseOrderLineProductId" style="max-width: 500px" data-autocomplete="true" data-no-wrap="true">
                            <option value="0">Select...</option>
                            {foreach from=$ns.products item=p}
                                <option value="{$p->getId()}">{$p->getName()}</option>
                            {/foreach}
                        </select>
                    </td>
                    <td>
                        <input class="text" type="number" step="0.1"  min="0.1" id="purchaseOrderLineQuantity"/>
                    </td>
                    <td>
                        <input class="text" type="number" step="0.01"  min="0.01" id="purchaseOrderLineUnitPrice"/>
                    </td>
                    <td>
                        <select id="purchaseOrderLineCurrencyId">
                            <option value="0">Select...</option>
                            {foreach from=$ns.currencies item=c}
                                <option value="{$c->getId()}">{$c->getName()} ({$c->getIso()} {$c->getTemplateChar()})</option>
                            {/foreach}
                        </select>
                    </td>
                    <td>
                        <a class="button_icon" href="javascript:void(0);" id="addPurchaseOrderLineButton" title="Add">
                            <i class="fa fa-plus-circle"></i>
                        </a>
                    </td>
                </tr>
            </table>
            <div>
                <span id="purchaseOrderLineErrorMessage" style="color:red"></span>
            </div>
        </div>

        <a class="button blue" href="javascript:void(0);" id="submitForm">Save</a>

    {/if}
</div>

<table style="display:none">
<tr id="purchaseOrderLineTemplate" style="display:none">
    <td>
        <select class="purchaseOrderLinesSelectProduct" style="max-width: 500px" data-no-wrap="true">
            {foreach from=$ns.products item=p}
                <option value="{$p->getId()}">{$p->getName()}</option>
            {/foreach}
        </select>
    </td>
    <td>
        <input  type="number" step="0.1"  min="0.1" class="purchaseOrderLinesSelectQuantity text"/>
    </td>
    <td>
        <input  type="number" step="0.01" min="0.01" class="purchaseOrderLinesSelectUnitPrice text"/>
    </td>
    <td>
        <select class="purchaseOrderLinesSelectCurrency">
            {foreach from=$ns.currencies item=c}
                <option value="{$c->getId()}">{$c->getName()} ({$c->getIso()} {$c->getTemplateChar()})</option>
            {/foreach}
        </select>
    </td>
    <td>
        <a class="button_icon removePurchaseOrderLine" title="delete">
            <i class="fa fa-trash-o"></i>
        </a>
        <input type="hidden" name="lines[]"/>
    </td>
</tr>

</table>