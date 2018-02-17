<div class="container rorder--open--container">
    <h1 class="main_title">Recipient Order View</h1>
    
    {if isset($ns.error_message)}
        {include file="{ngs cmd=get_template_dir}/main/message.tpl" type="error" content="{$ns.error_message}"} 
    {/if}
    {if isset($ns.success_message)}
        {include file="{ngs cmd=get_template_dir}/main/message.tpl" type="success" content="{$ns.success_message}"} 
    {/if}

    {if isset($ns.recipientOrder)}
        <div class="table_striped table_striped_simple">
            <div class="table-row">
                <span class="table-cell">
                    id :
                </span>
                <span class="table-cell">
                    {$ns.recipientOrder->getId()}
                </span>
            </div>
            <div class="table-row">
                <span class="table-cell">
                    Date :
                </span>
                <span class="table-cell">
                    {$ns.recipientOrder->getOrderDate()}
                </span>
            </div>
            <div class="table-row">
                <span class="table-cell">
                    Partner :
                </span>
                <span class="table-cell">
                    {$ns.recipientOrder->getRecipientDto()->getName()}
                </span>
            </div>
            <div class="table-row">
                <span class="table-cell">
                    Note :
                </span>
                <span class="table-cell">
                    {$ns.recipientOrder->getNote()}
                </span>
            </div>
        </div>
        {if $ns.recipientOrder->getCancelled() == 0}
            <form action="{$SITE_PATH}/dyn/main_rorder/do_cancel_recipient_order">
                <input type="hidden" name="id" id="recipient_order_id" value="{$ns.recipientOrder->getId()}"/>
                <div class="form-group">
                    <label class="label">Note</label>
                    <textarea class="text" name="note"></textarea>
                </div>
                <a class="button blue" id="cancelRecipientOrderButton" href="javascript:void(0);">Cancel</a>
            </form>
        {else}
            <a class="table-cell"  href="{$SITE_PATH}/dyn/main_rorder/do_restore_recipient_order?id={$ns.recipientOrder->getId()}">
                <span class="button blue">Restore</span>
            </a>
        {/if}
         <a class="button blue" href="{$SITE_PATH}/dyn/main_payment/do_redirect?partnerId={$ns.recipientOrder->getPartnerId()}&note=Payment for Recipient Order No-{$ns.recipientOrder->getId()}">Pay</a>
         
        <div class="checkbox_container">
            <div class="checkbox f_checkbox">
                <input type="checkbox" id="paidCheckbox" {if $ns.recipientOrder->getPaid()==1}checked{/if}/>
            </div>
            <label class="checkbox_label f_checkbox_label label">Paid</label>
        </div> 

        <form id="recipientOrderLinesForm" method="POST" action="{$SITE_PATH}/dyn/main_rorder/do_save_recipient_order_lines">
            <h2 class="title">Order Lines</h2>
            <div class="table_striped" id="recipientOrderLinesContainer">
                <div class="table_header_group">                  
                    <span class="table-cell"> Product </span>
                    <span class="table-cell"> Quantity </span>
                    <span class="table-cell"> Unit Price </span>
                    <span class="table-cell"> Currency </span>
                    <span class="table-cell"> Delete </span>
                </div> 
                {if $ns.recipientOrder->getRecipientOrderLinesDtos()|@count > 0}
                    {assign recipientOrderLines $ns.recipientOrder->getRecipientOrderLinesDtos()}
                    {foreach from=$recipientOrderLines item=recipientOrderLine}
                        <div class="recipientOrderLine table-row" line_id="{$recipientOrderLine->getId()}">
                            <div class="table-cell">
                                <select class="recipientOrderLinesSelectProduct" data-autocomplete="true" data-no-wrap="true">
                                    {foreach from=$ns.products item=p}
                                        <option value="{$p->getId()}" {if $p->getId() == $recipientOrderLine->getProductId()}selected{/if}>{$p->getName()}</option>
                                    {/foreach}
                                </select>
                            </div>
                            <div class="table-cell">
                                <input type="number" step="0.1"  min="0.1" class="recipientOrderLinesSelectQuantity text" value="{$recipientOrderLine->getQuantity()}"/>
                            </div>
                            <div class="table-cell">
                                <input type="number" step="0.01" min="0.01" class="recipientOrderLinesSelectUnitPrice text" value="{$recipientOrderLine->getUnitPrice()}"/>
                            </div>
                            <div class="table-cell">
                                <select class="recipientOrderLinesSelectCurrency">               
                                    {foreach from=$ns.currencies item=c}
                                        <option value="{$c->getId()}"  {if $c->getId() == $recipientOrderLine->getCurrencyId()}selected{/if}>
                                            {$c->getName()} ({$c->getIso()} {$c->getTemplateChar()})
                                        </option>
                                    {/foreach}
                                </select>
                            </div>
                            <div class="table-cell">
                                <a class="button_icon removeRecipientOrderLine" title="delete">
                                    <i class="fa fa-trash-o"></i>
                                </a>
                            </div>
                            <input type="hidden" name="lines[]"/>
                        </div>
                    {/foreach}
                {/if}
            </div>
            <input type="hidden" value="{$ns.recipientOrder->getId()}" name="recipient_order_id"/>
        </form>

        {*                           ADD NEW Recipient OREDER LINE                                 *}
        <div class="table_striped add_new_recipient_order_line">
            <div class="table-row">
                <div class="table-cell">
                    <select id="recipientOrderLineProductId" data-autocomplete="true" data-no-wrap="true">                       
                        <option value="0">Select...</option>
                        {foreach from=$ns.products item=p}
                            <option value="{$p->getId()}">{$p->getName()}</option>
                        {/foreach}
                    </select>
                </div>
                <div class="table-cell">
                    <input class="text" type="number" step="0.1"  min="0.1" id="recipientOrderLineQuantity"/>
                </div>
                <div class="table-cell">
                    <input class="text" type="number" step="0.01"  min="0.01" id="recipientOrderLineUnitPrice"/>
                </div>
                <div class="table-cell">
                    <select id="recipientOrderLineCurrencyId">               
                        <option value="0">Select...</option>
                        {foreach from=$ns.currencies item=c}
                            <option value="{$c->getId()}">{$c->getName()} ({$c->getIso()} {$c->getTemplateChar()})</option>
                        {/foreach}
                    </select>
                </div>
                <div class="table-cell">
                    <a class="button_icon" href="javascript:void(0);" id="addRecipientOrderLineButton" title="Add">
                        <i class="fa fa-plus-circle"></i>
                    </a>
                </div>
            </div>
            <div>
                <span id="recipientOrderLineErrorMessage" style="color:red"></span>
            </div>
        </div>

        <a class="button blue" href="javascript:void(0);" id="submitForm">Save</a>

    {/if}
</div>


<div class="table-row" id="recipientOrderLineTemplate" style="display:none">
    <div class="table-cell">
        <select class="recipientOrderLinesSelectProduct"  data-no-wrap="true">
            {foreach from=$ns.products item=p}
                <option value="{$p->getId()}">{$p->getName()}</option>
            {/foreach}
        </select>
    </div>
    <div class="table-cell">
        <input  type="number" step="0.1"  min="0.1" class="recipientOrderLinesSelectQuantity text"/>
    </div>
    <div class="table-cell">
        <input  type="number" step="0.01" min="0.01" class="recipientOrderLinesSelectUnitPrice text"/>
    </div>
    <div class="table-cell">
        <select class="recipientOrderLinesSelectCurrency">               
            {foreach from=$ns.currencies item=c}
                <option value="{$c->getId()}">{$c->getName()} ({$c->getIso()} {$c->getTemplateChar()})</option>
            {/foreach}
        </select>
    </div>
    <div class="table-cell">
        <a class="button_icon removeRecipientOrderLine" title="delete">
            <i class="fa fa-trash-o"></i>
        </a>
    </div>
    <input type="hidden" name="lines[]"/>
</div>
