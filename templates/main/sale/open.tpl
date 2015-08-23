<div class="container sale--open--container">
    <h1>Sale Orders View</h1>

    {if isset($ns.error_message)}
        {include file="{getTemplateDir}/main/message.tpl" type="error" content="{$ns.error_message}"} 
    {/if}
    {if isset($ns.success_message)}
        {include file="{getTemplateDir}/main/message.tpl" type="success" content="{$ns.success_message}"} 
    {/if}

    {if isset($ns.saleOrder)}
        <div class="table_striped table_striped_simple">
            <div class="table-row">
                <span class="table-cell">
                    id :
                </span>
                <span class="table-cell">
                    {$ns.saleOrder->getId()}
                </span>
            </div>
            <div class="table-row">
                <span class="table-cell">
                    Date :
                </span>
                <span class="table-cell">
                    {$ns.saleOrder->getOrderDate()}
                </span>
            </div>
            <div class="table-row">
                <span class="table-cell">
                    Partner :
                </span>
                <span class="table-cell">
                    {$ns.saleOrder->getPartnerDto()->getName()}
                </span>
            </div>
            <div class="table-row">
                <span class="table-cell">
                    Note :
                </span>
                <span class="table-cell">
                    {$ns.saleOrder->getNote()}
                </span>
            </div>
        </div>
        {if $ns.saleOrder->getCancelled() == 0}
            <form action="{SITE_PATH}/dyn/main_sale/do_cancel_sale_order">
                <input type="hidden" name="id" id="sale_order_id" value="{$ns.saleOrder->getId()}"/>
                <div class="form-group">
                    <label class="label">Note</label>
                    <textarea class="text" name="note"></textarea>
                </div>
                <a class="button blue" id="cancelSaleOrderButton" href="javascript:void(0);">Cancel</a>
            </form>
        {else}
            <a class="table-cell"  href="{SITE_PATH}/dyn/main_sale/do_restore_sale_order?id={$ns.saleOrder->getId()}">
                <span class="button blue">Restore</span>
            </a>
        {/if}

        <div class="checkbox_container">
            <div class="checkbox f_checkbox">
                <input type="checkbox" id="billedCheckbox" {if $ns.saleOrder->getBilled()==1}checked{/if}/>
            </div>
            <label class="checkbox_label f_checkbox_label label">Billed</label>
        </div> 

        <form id="saleOrderLinesForm" method="POST" action="{SITE_PATH}/dyn/main_sale/do_save_sale_order_lines">
            <h2 class="title">Order Lines</h2>
            <div class="table_striped" id="saleOrderLinesContainer">
                <div class="table_header_group">                    
                    <span class="table-cell"> Product </span>
                    <span class="table-cell"> Quantity </span>
                    <span class="table-cell"> Unit Price </span>
                    <span class="table-cell"> Currency </span>
                    <span class="table-cell"> Delete </span>
                </div> 
                {if $ns.saleOrder->getSaleOrderLinesDtos()|@count > 0}
                    {assign saleOrderLines $ns.saleOrder->getSaleOrderLinesDtos()}
                    {foreach from=$saleOrderLines item=saleOrderLine}
                        <div class="saleOrderLine table-row">
                            <div class="table-cell">
                                <select class="saleOrderLinesSelectProduct" data-autocomplete="true">
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
                                <select class="saleOrderLinesSelectCurrency">               
                                    {foreach from=$ns.currencies item=c}
                                        <option value="{$c->getId()}"  {if $c->getId() == $saleOrderLine->getCurrencyId()}selected{/if}>
                                            {$c->getName()} ({$c->getIso()} {$c->getTemplateChar()})
                                        </option>
                                    {/foreach}
                                </select>
                            </div>
                            <div class="table-cell">
                                <a class="button_icon removeSaleOrderLine" title="delete">
                                    <i class="fa fa-trash-o"></i>
                                </a>
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
                    <select id="saleOrderLineProductId" data-autocomplete="true">                       
                        <option value="0">Select...</option>
                        {foreach from=$ns.products item=p}
                            <option value="{$p->getId()}">{$p->getName()}</option>
                        {/foreach}
                    </select>
                    <span id="saleOrderLineProductStockCount" style="color:green"></span>
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
                    <a class="button_icon" href="javascript:void(0);" id="addSaleOrderLineButton" title="Add">
                        <i class="fa fa-plus-circle"></i>
                    </a>
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
        <select class="saleOrderLinesSelectProduct" data-autocomplete="true">
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
        <a class="button_icon removeSaleOrderLine" title="delete">
            <i class="fa fa-trash-o"></i>
        </a>
    </div>
    <input type="hidden" name="lines[]"/>
</div>
