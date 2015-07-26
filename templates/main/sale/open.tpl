<div>
    {include file="{getTemplateDir}/main/left_menu.tpl"}
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
                <div>
                    <label>Note</label>
                    <textarea  name="note"></textarea>
                </div>
                <a id="cancelSaleOrderButton" href="javascript:void(0);">Cancel</a>
            </form>
        {else}
            Cancelled
        {/if}
        <form id="saleORderLinesForm" method="POST" action="{SITE_PATH}/dyn/main/do_save_sale_order_lines">
            <h2>Order Lines</h2>
            <div>
                <span> ID </span>
                <span> Product </span>
                <span> Quantity </span>
                <span> Unit Price </span>
                <span> Currency </span>
            </div> 
            {if $ns.saleOrder->getSaleOrderLinesDtos()|@count > 0}
                {assign saleOrderLines $ns.saleOrder->getSaleOrderLinesDtos()}
                {foreach from=$saleOrderLines item=saleOrderLine}
                    <div class="saleOrderLine">
                        <div style="float:left">
                            <select class="saleOrderLinesSelectProduct">
                                {foreach from=$ns.products item=p}
                                    <option value="{$p->getId()}" {if $p->getId() == $saleOrderLine->getProductId()}selected{/if}>{$p->getName()}</option>
                                {/foreach}
                            </select>
                        </div>
                        <div  style="float:left">
                            <input type="number" step="0.1"  min="0.1" class="saleOrderLinesSelectQuantity" value="{$saleOrderLine->getQuantity()}"/>
                        </div>
                        <div  style="float:left">
                            <input type="number" step="0.01" min="0.01" class="saleOrderLinesSelectUnitPrice" value="{$saleOrderLine->getUnitPrice()}"/>
                        </div>
                        <div  style="float:left">
                            <select class="saleOrderLinesSelectCurrency">               
                                {foreach from=$ns.currencies item=c}
                                    <option value="{$c->getId()}"  {if $c->getId() == $saleOrderLine->getCurrencyId()}selected{/if}>
                                        {$c->getName()} ({$c->getIso()} {$c->getTemplateChar()})
                                    </option>
                                {/foreach}
                            </select>
                        </div>
                        <input type="hidden" name="lines[]"/>
                        <div style="clear: both"></div>
                    </div>
                {/foreach}
            {/if}
            <div id="saleOrderLinesContainer"></div>
            <input type="hidden" value="{$ns.saleOrder->getId()}" name="sale_order_id"/>
        </form>
        <div>
            <div style="float:left">
                <select id="saleOrderLineProductId">                       
                    <option value="0">Select...</option>
                    {foreach from=$ns.products item=p}
                        <option value="{$p->getId()}">{$p->getName()}</option>
                    {/foreach}
                </select>
            </div>
            <div  style="float:left">
                <input type="number" step="0.1"  min="0.1" id="saleOrderLineQuantity"/>
            </div>
            <div  style="float:left">
                <input type="number" step="0.01"  min="0.01" id="saleOrderLineUnitPrice"/>
            </div>
            <div style="float:left">
                <select id="saleOrderLineCurrencyId">               
                    <option value="0">Select...</option>
                    {foreach from=$ns.currencies item=c}
                        <option value="{$c->getId()}">{$c->getName()} ({$c->getIso()} {$c->getTemplateChar()})</option>
                    {/foreach}
                </select>
            </div>
            <div  style="float:left">
                <a href="javascript:void(0);" id="addSaleOrderLineButton">Add</a>
            </div>
            <div style="clear: both"></div>
        </div>

        <a href="javascript:void(0);" id="submitForm">Save</a>

    {/if}
</div>


<div id="saleOrderLineTemplate" style="display:none">
    <div style="float:left">
        <select class="saleOrderLinesSelectProduct">
            {foreach from=$ns.products item=p}
                <option value="{$p->getId()}">{$p->getName()}</option>
            {/foreach}
        </select>
    </div>
    <div  style="float:left">
        <input type="number" step="0.1"  min="0.1" class="saleOrderLinesSelectQuantity"/>
    </div>
    <div  style="float:left">
        <input type="number" step="0.01" min="0.01" class="saleOrderLinesSelectUnitPrice"/>
    </div>
    <div  style="float:left">
        <select class="saleOrderLinesSelectCurrency">               
            {foreach from=$ns.currencies item=c}
                <option value="{$c->getId()}">{$c->getName()} ({$c->getIso()} {$c->getTemplateChar()})</option>
            {/foreach}
        </select>
    </div>
    <input type="hidden" name="lines[]"/>
    <div style="clear: both"></div>
</div>
