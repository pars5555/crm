<div class="container preorder--open--container">
    <h1 class="main_title">Preorder View</h1>

    {if isset($ns.error_message)}
        {include file="{ngs cmd=get_template_dir}/main/message.tpl" type="error" content="{$ns.error_message}"}
    {/if}
    {if isset($ns.success_message)}
        {include file="{ngs cmd=get_template_dir}/main/message.tpl" type="success" content="{$ns.success_message}"}
    {/if}

    {if isset($ns.preorder)}
        <div class="table_striped table_striped_simple">
            <div class="table-row">
                <span class="table-cell">
                    id :
                </span>
                <span class="table-cell">
                    {$ns.preorder->getId()}
                </span>
            </div>
            <div class="table-row">
                <span class="table-cell">
                    Date :
                </span>
                <span class="table-cell">
                    {$ns.preorder->getOrderDate()}
                </span>
            </div>
            <div class="table-row">
                <span class="table-cell">
                    Partner :
                </span>
                <span class="table-cell">
                    {$ns.preorder->getPartnerDto()->getName()}
                </span>
            </div>
            <div class="table-row">
                <span class="table-cell">
                    Note :
                </span>
                <span class="table-cell">
                    {$ns.preorder->getNote()}
                </span>
            </div>
        </div>

        {if $ns.preorder->getCancelled() == 0}
            <form action="{$SITE_PATH}/dyn/main_preorder/do_cancel_preorder">
                <input type="hidden" name="id" id="preorder_id" value="{$ns.preorder->getId()}"/>
                <div class="form-group">
                    <label class="label">Note</label>
                    <textarea class="text comment" name="note"></textarea>
                </div>
                <div class="open-section-buttons">
                    <a class="button blue" id="cancelPreorderButton" href="javascript:void(0);">Cancel</a>
                    <a class="button blue" href="{$SITE_PATH}/dyn/main_payment/do_redirect?partnerId={$ns.preorder->getPartnerId()}&note=Payment for Preorder No-{$ns.preorder->getId()}">Pay</a>
                </div>
            </form>
        {else}
            <div class="open-section-buttons">
                <a class="button blue" href="{$SITE_PATH}/dyn/main_preorder/do_restore_preorder?id={$ns.preorder->getId()}">Restore</a>
                <a class="button blue" href="{$SITE_PATH}/dyn/main_payment/do_redirect?partnerId={$ns.preorder->getPartnerId()}&note=Payment for Preorder No-{$ns.preorder->getId()}">Pay</a>
            </div>
        {/if}

        <div class="checkbox_container">
            <div class="checkbox f_checkbox">
                <input type="checkbox" id="paidCheckbox" {if $ns.preorder->getPaid()==1}checked{/if}/>
            </div>
            <label class="checkbox_label f_checkbox_label label">Paid</label>
        </div>

        <form id="preorderLinesForm" method="POST" action="{$SITE_PATH}/dyn/main_preorder/do_save_preorder_lines">
            <h2 class="title">Order Lines</h2>
            <label>Search</label>
            <input id="search_item" type="text" class="text"/>
            <div class="table_striped" id="preorderLinesContainer">
                <div class="table_header_group">
                    <span class="table-cell"> Product </span>
                    <span class="table-cell"> Quantity </span>
                    <span class="table-cell"> Unit Price </span>
                    <span class="table-cell"> Currency </span>
                    <span class="table-cell"> Delete </span>
                </div>
                {if $ns.preorder->getPreorderLinesDtos()|@count > 0}
                    {assign preorderLines $ns.preorder->getPreorderLinesDtos()}
                    {foreach from=$preorderLines item=preorderLine}
                        <div class="preorderLine table-row" line_id="{$preorderLine->getId()}">
                            <div class="table-cell">
                                {foreach from=$ns.products item=p}
                                    {if $p->getId() == $preorderLine->getProductId()}
                                        {assign productName $p->getName()}
                                    {/if}
                                {/foreach}
                                <select class="preorderLinesSelectProduct" title="{$productName}" style="max-width: 500px" data-autocomplete="true" data-no-wrap="true">
                                    {foreach from=$ns.products item=p}
                                        <option value="{$p->getId()}" {if $p->getId() == $preorderLine->getProductId()}selected{/if}>{$p->getName()}</option>
                                    {/foreach}
                                </select>
                            </div>
                            <div class="table-cell">
                                <input type="number" step="0.1"  min="0.1" class="preorderLinesSelectQuantity text" value="{$preorderLine->getQuantity()}"/>
                            </div>
                            <div class="table-cell">
                                <input type="number" step="0.01" min="0.01" class="preorderLinesSelectUnitPrice text" value="{$preorderLine->getUnitPrice()}"/>
                            </div>
                            <div class="table-cell">
                                <select class="preorderLinesSelectCurrency">
                                    {foreach from=$ns.currencies item=c}
                                        <option value="{$c->getId()}"  {if $c->getId() == $preorderLine->getCurrencyId()}selected{/if}>
                                            {$c->getName()} ({$c->getIso()} {$c->getTemplateChar()})
                                        </option>
                                    {/foreach}
                                </select>
                            </div>
                            <div class="table-cell">
                                <a class="button_icon removePreorderLine" title="delete">
                                    <i class="fa fa-trash-o"></i>
                                </a>
                            </div>
                            <input type="hidden" name="lines[]"/>
                        </div>
                    {/foreach}
                {/if}
            </div>
            <input type="hidden" value="{$ns.preorder->getId()}" name="preorder_id"/>
        </form>

        {*                           ADD NEW PREORDER OREDER LINE                                 *}
        <div class="table_striped add_new_preorder_line">
            <div class="table-row">
                <div class="table-cell">
                    <select id="preorderLineProductId" style="max-width: 500px" data-autocomplete="true" data-no-wrap="true">
                        <option value="0">Select...</option>
                        {foreach from=$ns.products item=p}
                            <option value="{$p->getId()}">{$p->getName()}</option>
                        {/foreach}
                    </select>
                </div>
                <div class="table-cell">
                    <input class="text" type="number" step="0.1"  min="0.1" id="preorderLineQuantity"/>
                </div>
                <div class="table-cell">
                    <input class="text" type="number" step="0.01"  min="0.01" id="preorderLineUnitPrice"/>
                </div>
                <div class="table-cell">
                    <select id="preorderLineCurrencyId">
                        <option value="0">Select...</option>
                        {foreach from=$ns.currencies item=c}
                            <option value="{$c->getId()}">{$c->getName()} ({$c->getIso()} {$c->getTemplateChar()})</option>
                        {/foreach}
                    </select>
                </div>
                <div class="table-cell">
                    <a class="button_icon" href="javascript:void(0);" id="addPreorderLineButton" title="Add">
                        <i class="fa fa-plus-circle"></i>
                    </a>
                </div>
            </div>
            <div>
                <span id="preorderLineErrorMessage" style="color:red"></span>
            </div>
        </div>

        <a class="button blue" href="javascript:void(0);" id="submitForm">Save</a>

    {/if}
</div>


<div class="table-row" id="preorderLineTemplate" style="display:none">
    <div class="table-cell">
        <select class="preorderLinesSelectProduct" style="max-width: 500px" data-no-wrap="true">
            {foreach from=$ns.products item=p}
                <option value="{$p->getId()}">{$p->getName()}</option>
            {/foreach}
        </select>
    </div>
    <div class="table-cell">
        <input  type="number" step="0.1"  min="0.1" class="preorderLinesSelectQuantity text"/>
    </div>
    <div class="table-cell">
        <input  type="number" step="0.01" min="0.01" class="preorderLinesSelectUnitPrice text"/>
    </div>
    <div class="table-cell">
        <select class="preorderLinesSelectCurrency">
            {foreach from=$ns.currencies item=c}
                <option value="{$c->getId()}">{$c->getName()} ({$c->getIso()} {$c->getTemplateChar()})</option>
            {/foreach}
        </select>
    </div>
    <div class="table-cell">
        <a class="button_icon removePreorderLine" title="delete">
            <i class="fa fa-trash-o"></i>
        </a>
    </div>
    <input type="hidden" name="lines[]"/>
</div>

{include file="{ngs cmd=get_template_dir}/main/util/attachments.tpl"} 
            
<form id="upload_attachment_form" target="upload_target" enctype="multipart/form-data" method="post" action="{$SITE_PATH}/dyn/attachment/do_upload" autocomplete="off">
    <a class="button blue" id="select_attachment_button" >select attachment...</a>
    <input type="hidden" name="entity_id" value="{$ns.preorder->getId()}"/>
    <input type="hidden" name="entity_name" value="preorder"/>
    <input type="hidden" name="partner_id" value="{$ns.preorder->getPartnerId()}"/>
    <input id="file_input" name="file" type="file" style="display:none" />
</form>
<iframe id="upload_target" name="upload_target" style="width:0;height:0;border:0px solid #fff;display: none;" ></iframe>
