<div class="container sale--open--container">
    <h1 class="main_title">Sale Orders View</h1>

    {if isset($ns.error_message)}
        {include file="{ngs cmd=get_template_dir}/main/message.tpl" type="error" content="{$ns.error_message}"}
    {/if}
    {if isset($ns.success_message)}
        {include file="{ngs cmd=get_template_dir}/main/message.tpl" type="success" content="{$ns.success_message}"}
    {/if}

    {if isset($ns.saleOrder)}
        <div class="main-table">
            <table>
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
                {if $ns.showprofit == 1}
                    <tr>
                        <td>Profit:</td>
                        <td>
                            {$ns.saleOrder->getTotalProfit()}
                        </td>
                    </tr>
                {/if}
                <tr>
                    <td>Note:</td>
                    <td>
                        {$ns.saleOrder->getNote()}
                    </td>
                </tr>
            </table>
        </div>
        {if $ns.saleOrder->getCancelled() == 0}
            <form action="{$SITE_PATH}/dyn/main_sale/do_cancel_sale_order">
                <input type="hidden" name="id" id="sale_order_id" value="{$ns.saleOrder->getId()}"/>
                <div class="form-group">
                    <label class="label">Note</label>
                    <textarea class="text comment" name="note"></textarea>
                </div>
                <div class="open-section-buttons">
                    <a class="button blue" id="cancelSaleOrderButton" href="javascript:void(0);">Cancel</a>
                    <a class="button blue" href="{$SITE_PATH}/dyn/main_billing/do_redirect?partnerId={$ns.saleOrder->getPartnerId()}&note=Billing for Sale Order No-{$ns.saleOrder->getId()}">Bill</a>
                    <a class="button blue" href="{$SITE_PATH}/sale/warranty/{$ns.saleOrder->getId()}">Warranty</a>
                </div>
            </form>
        {else}
            <div class="open-section-buttons">
                <a class="button blue" href="{$SITE_PATH}/dyn/main_sale/do_restore_sale_order?id={$ns.saleOrder->getId()}">Restore</a>
                <a class="button blue" href="{$SITE_PATH}/dyn/main_billing/do_redirect?partnerId={$ns.saleOrder->getPartnerId()}&note=Billing for Sale Order No-{$ns.saleOrder->getId()}">Bill</a>
                <a class="button blue" href="{$SITE_PATH}/sale/warranty/{$ns.saleOrder->getId()}">Warranty</a>
            </div>
        {/if}

        <div class="checkbox_container">
            <div class="checkbox f_checkbox">
                <input type="checkbox" id="nonProfitCheckbox" {if $ns.saleOrder->getNonProfit()==1}checked{/if}/>
            </div>
            <label class="checkbox_label f_checkbox_label label">Non Profit</label>
        </div>
        <div class="checkbox_container">
            <div class="checkbox f_checkbox">
                <input type="checkbox" id="billedCheckbox" {if $ns.saleOrder->getBilled()==1}checked{/if}/>
            </div>
            <label class="checkbox_label f_checkbox_label label">Billed</label>
        </div>

        <form id="saleOrderLinesForm" method="POST" action="{$SITE_PATH}/dyn/main_sale/do_save_sale_order_lines">
            <h2 class="title">Order Lines</h2>
            <label>Search</label>
            <input id="search_item" type="text" class="text"/>
            <div class="table_striped" id="purchaseOrderLinesContainer">
                <div class="main-table">
                    <table id="saleOrderLinesContainer">
                        <tr>
                            <th> Product </th>
                            <th> Quantity </th>
                            <th> Unit Price </th>
                            <th> Currency </th>
                            <th> Total </th>
                            <th> Profit </th>
                            <th> Delete </th>
                        </tr>
                        {if $ns.saleOrder->getSaleOrderLinesDtos()|@count > 0}
                            {assign saleOrderLines $ns.saleOrder->getSaleOrderLinesDtos()}
                            {foreach from=$saleOrderLines item=saleOrderLine}
                                <tr class="saleOrderLine" line_id="{$saleOrderLine->getId()}" >
                                    <td>
                                        {foreach from=$ns.products item=p}
                                            {if $p->getId() == $saleOrderLine->getProductId()}
                                                {assign productName $p->getName()}
                                            {/if}
                                        {/foreach}
                                        <select class="saleOrderLinesSelectProduct" title="{$productName}" style="max-width: 500px" disabled>
                                            {foreach from=$ns.products item=p}
                                                <option value="{$p->getId()}" {if $p->getId() == $saleOrderLine->getProductId()}selected{/if}>{$p->getName()}</option>
                                            {/foreach}
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" step="0.1"  min="0.1" class="saleOrderLinesSelectQuantity text" value="{$saleOrderLine->getQuantity()}"/>
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" min="0.01" class="saleOrderLinesSelectUnitPrice text" value="{$saleOrderLine->getUnitPrice()}"/>
                                    </td>
                                    <td>
                                        <select class="saleOrderLinesSelectCurrency">
                                            {foreach from=$ns.currencies item=c}
                                                <option value="{$c->getId()}" iso="{$c->getIso()}" symbol="{$c->getTemplateChar()}" position="{$c->getSymbolPosition()}" {if $c->getId() == $saleOrderLine->getCurrencyId()}selected{/if}>
                                                    {$c->getName()} ({$c->getIso()} {$c->getTemplateChar()})
                                                </option>
                                            {/foreach}
                                        </select>
                                    </td>
                                    <td>
                                        <span class="saleOrderLinesTotal"></span>
                                    </td>
                                    <td>
                                        {$saleOrderLine->getTotalProfit()}
                                    </td>
                                    <td>
                                        <a class="button_icon removeSaleOrderLine" title="delete">
                                            <i class="fa fa-trash-o"></i>
                                        </a>
                                        <input type="hidden" name="lines[]"/>
                                    </td>
                                </tr>
                            {/foreach}
                        {/if}
                    </table>
                </div>
                <input type="hidden" value="{$ns.saleOrder->getId()}" name="sale_order_id"/>
        </form>

        {*                           ADD NEW SALE OREDER LINE                                 *}
        <div class="main-table has-dropdown">
            <table>
                <tr>
                    <td>
                        <select id="saleOrderLineProductId" style="max-width: 500px" data-autocomplete="true">
                            <option value="0">Select...</option>
                            {foreach from=$ns.products item=p}
                                <option value="{$p->getId()}">{$p->getName()}</option>
                            {/foreach}
                        </select>
                        <span id="saleOrderLineProductStockCount" style="max-width: 500px" style="color:green"></span>
                    </td>
                    <td>
                        <input class="text" type="number" step="0.1"  min="0.1" id="saleOrderLineQuantity"/>
                    </td>
                    <td>
                        <input class="text" type="number" step="0.01"  min="0.01" id="saleOrderLineUnitPrice"/>
                    </td>
                    <td>
                        <select id="saleOrderLineCurrencyId">
                            <option value="0">Select...</option>
                            {foreach from=$ns.currencies item=c}
                                <option value="{$c->getId()}">{$c->getName()} ({$c->getIso()} {$c->getTemplateChar()})</option>
                            {/foreach}
                        </select>
                    </td>
                    <td>
                        <a class="button_icon" href="javascript:void(0);" id="addSaleOrderLineButton" title="Add">
                            <i class="fa fa-plus-circle"></i>
                        </a>
                    </td>
                </tr>
            </table>
            <div>
                <span id="saleOrderLineErrorMessage" style="color:red"></span>
            </div>
        </div>

        <a class="button blue" href="javascript:void(0);" id="submitForm">Save</a>
        <div id="saleOrderTotalAmount"></div>
    {/if}
</div>


<div class="table-row" id="saleOrderLineTemplate" style="display:none">
    <div class="table-cell">
        <select class="saleOrderLinesSelectProduct"  style="max-width: 500px" disabled>
            {foreach from=$ns.products item=p}
                <option value="{$p->getId()}">{$p->getName()}</option>
            {/foreach}
        </select>
    </div>
    <div class="table-cell">
        <input  type="number" step="0.1"  min="0.1" class="saleOrderLinesSelectQuantity text"/>
    </div>
    <div class="table-cell">
        <input  type="number" step="0.01" min="0.01" class="saleOrderLinesSelectUnitPrice text"/>
    </div>
    <div class="table-cell">
        <select class="saleOrderLinesSelectCurrency">
            {foreach from=$ns.currencies item=c}
                <option value="{$c->getId()}" iso="{$c->getIso()}" symbol="{$c->getTemplateChar()}" position="{$c->getSymbolPosition()}" >
                    {$c->getName()} ({$c->getIso()} {$c->getTemplateChar()})</option>
                {/foreach}
        </select>
    </div>
    <div class="table-cell">
        <span class="saleOrderLinesTotal"></span>
    </div>
    <div class="table-cell">
        <a class="button_icon removeSaleOrderLine" title="delete">
            <i class="fa fa-trash-o"></i>
        </a>
    </div>
    <input type="hidden" name="lines[]"/>
</div>
