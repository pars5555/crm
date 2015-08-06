<div class="container sale--open--container">
    {if isset($ns.error_message)}
        <div>
            <span style="color:red">{$ns.error_message}</span>
        </div>
    {/if}
    {if isset($ns.success_message)}
        <div>
            <span style="color:green">{$ns.success_message}</span>
        </div>
    {/if}
    {if isset($ns.saleOrder)}
        <div>
            id: {$ns.saleOrder->getId()}
        </div>
        <div>
            Date: {$ns.saleOrder->getOrderDate()}
        </div>
        <div>
            Partner: {$ns.saleOrder->getPartnerDto()->getName()}
        </div>
        <div>
            Note : {$ns.saleOrder->getNote()}
        </div>
        {if $saleOrder->getCancelled() == 0}
            <form action="{SITE_PATH}/dyn/main/do_cancel_sale_order">
                <input type="hidden" name="id" value="{$ns.saleOrder->getId()}"/>
                <div class="form-group">
                    <label class="label">Note</label>
                    <textarea class="text" name="note"></textarea>
                </div>
                <a class="button blue" id="cancelSaleOrderButton" href="javascript:void(0);">Cancel</a>
            </form>
        {else}
            Cancelled
        {/if}
        <form id="saleOrderLinesForm" method="POST" action="{SITE_PATH}/dyn/main/do_save_sale_order_lines">
            <h2 class="title">Order Lines</h2>
            <div class="table_striped" id="saleOrderLinesContainer">
                <div class="table_header_group">                    
                    <span class="table-cell"> Product </span>
                    <span class="table-cell"> Quantity </span>
                    <span class="table-cell"> Unit Price </span>
                    <span class="table-cell"> Currency </span>
                </div> 
                {if $ns.saleOrder->getSaleOrderLinesDtos()|@count > 0}
                    {assign saleOrderLines $ns.saleOrder->getSaleOrderLinesDtos()}
                    {foreach from=$saleOrderLines item=saleOrderLine}
                        <div class="saleOrderLine table-row">
                            <div class="table-cell">
                                <select class="saleOrderLinesSelectProduct">
                                    {foreach from=$ns.products item=p}
                                        <option value="{$p->getId()}" {if $p->getId() == $saleOrderLine->getProductId()}selected{/if}>{$p->getName()}</option>
                                    {/foreach}
                                </select>
                            </div>
                            <div class="table-cell">
                                <input type="number" step="0.1"  min="0.1" class="saleOrderLinesSelectQuantity text" value="{$saleOrderLine->getQuantity()}"/>
                            </div>
                            <div class="table-cell">
                                <input type="number" step="0.01" min="0.01" class="saleOrderLinesSelectUnitPrice text" value="{$saleOrderLine->getUnitPrice()}"/>
                            </div>
                            <div class="table-cell">
                            </div>
                            <div class="table-cell">
                                <select class="saleOrderLinesSelectCurrency">               
                                    {foreach from=$ns.currencies item=c}
                                        <option value="{$c->getId()}"  {if $c->getId() == $saleOrderLine->getCurrencyId()}selected{/if}>
                                            {$c->getName()} ({$c->getIso()} {$c->getTemplateChar()})
                                        </option>
                                    {/foreach}
                                </select>
                            </div>
                            <div class="table-cell">
                                <a class="button blue removeSaleOrderLine">Remove</a>
                            </div>
                            <input type="hidden" name="lines[]"/>
                        </div>
                    {/foreach}
                {/if}
            </div>
            <input type="hidden" value="{$ns.saleOrder->getId()}" name="sale_order_id"/>
        </form>

        {*                           ADD NEW SALE OREDER LINE                                 *}
        <div class="table_striped add_new_sale_order_line">
            <div class="table-row">
                <div class="table-cell">
                    <select id="saleOrderLineProductId">                       
                        <option value="0">Select...</option>
                        {foreach from=$ns.products item=p}
                            <option value="{$p->getId()}">{$p->getName()}</option>
                        {/foreach}
                    </select>
                </div>
                <div class="table-cell">
                    <input class="text" type="number" step="0.1"  min="0.1" id="saleOrderLineQuantity"/>
                </div>
                <div class="table-cell">
                    <input class="text" type="number" step="0.01"  min="0.01" id="saleOrderLineUnitPrice"/>
                </div>
                <div class="table-cell">
                    <select id="saleOrderLineCurrencyId">               
                        <option value="0">Select...</option>
                        {foreach from=$ns.currencies item=c}
                            <option value="{$c->getId()}">{$c->getName()} ({$c->getIso()} {$c->getTemplateChar()})</option>
                        {/foreach}
                    </select>
                </div>
                <div class="table-cell">
                    <a class="button blue" href="javascript:void(0);" id="addSaleOrderLineButton">Add</a>
                </div>
            </div>
            <div>
                <span id="saleOrderLineErrorMessage" style="color:red"></span>
            </div>
        </div>

        <a class="button blue" href="javascript:void(0);" id="submitForm">Save</a>

    {/if}
</div>


<div class="table-row" id="saleOrderLineTemplate" style="display:none">
    <div class="table-cell">
        <select class="saleOrderLinesSelectProduct">
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
    </div>
    <div class="table-cell">
        <select class="saleOrderLinesSelectCurrency">               
            {foreach from=$ns.currencies item=c}
                <option value="{$c->getId()}">{$c->getName()} ({$c->getIso()} {$c->getTemplateChar()})</option>
            {/foreach}
        </select>
    </div>
    <div class="table-cell">
        <a class="button blue removeSaleOrderLine">Remove</a>
    </div>
    <input type="hidden" name="lines[]"/>
</div>
