<div class="container partner--create--container">
    <h1 class="main_title">Update Partner</h1>

    {if isset($ns.error_message)}
        {include file="{ngs cmd=get_template_dir}/main/message.tpl" type="error" content="{$ns.error_message}"} 
    {/if}
    {if isset($ns.success_message)}
        {include file="{ngs cmd=get_template_dir}/main/message.tpl" type="success" content="{$ns.success_message}"} 
    {/if}

    {if isset($ns.partner)}
        <form id="updatePartnerForm" class="create--form" autocomplete="off" method="post" action="{$SITE_PATH}/dyn/main_partner/do_update_partner">
            <div class="form-group">
                <label class="label">Name</label>
                <input class="text" type="text" name="name" value="{$ns.req.name|default:''}"/>
            </div>
            <div class="form-group">
                <label class="label">Email</label>
                <input class="text" type="email" name="email" value="{$ns.req.email|default:''}"/>
            </div>
            <div class="form-group">
                <label class="label">Address</label>
                <input class="text" type="text" name="address" value="{$ns.req.address|default:''}"/>
            </div>
            <div class="form-group">
                <label class="label">Phone</label>
                <input class="text" type="text" name="phone" value="{$ns.req.phone|default:''}"/>
            </div>
            <div class="form-group">
                <label class="label">Initial Dept</label>
                <div id="initialDeptContainer">
                    {foreach from=$ns.partner->getPartnerInitialDeptDtos() item=initialDeptDto}
                        <div class="initialDeptRow table-row" line_id="{$initialDeptDto->getId()}" >
                            <div class="table-cell">
                                <input type="number" class="initialDeptAmount text" value="{$initialDeptDto->getAmount()}"/>
                            </div>
                            <div class="table-cell">
                                <select class="initialDeptSelectCurrency">               
                                    {foreach from=$ns.currencies item=c}
                                        <option value="{$c->getId()}" iso="{$c->getIso()}" symbol="{$c->getTemplateChar()}" position="{$c->getSymbolPosition()}" {if $c->getId() == $initialDeptDto->getCurrencyId()}selected{/if}>
                                            {$c->getName()} ({$c->getIso()} {$c->getTemplateChar()})
                                        </option>
                                    {/foreach}
                                </select>
                            </div>
                            <div class="table-cell">
                                <input type="text" class="initialDeptNote text" value="{$initialDeptDto->getNote()}"/>
                            </div>
                            <div class="table-cell">
                                <a class="button_icon removeInitialDept" title="delete">
                                    <i class="fa fa-trash-o"></i>
                                </a>
                            </div>
                            <input type="hidden" name="initialDepts[]"/>
                        </div>
                    {/foreach}
                </div>
                <div class="initialDeptRow table-row">
                    <div class="table-cell">
                        <input type="number" id="initialDeptAmount" class="text" value=""/>
                    </div>
                    <div class="table-cell">
                        <select id="initialDeptSelectCurrency">               
                            {foreach from=$ns.currencies item=c}
                                <option value="{$c->getId()}" iso="{$c->getIso()}" symbol="{$c->getTemplateChar()}" position="{$c->getSymbolPosition()}" >
                                    {$c->getName()} ({$c->getIso()} {$c->getTemplateChar()})
                                </option>
                            {/foreach}
                        </select>
                    </div>
                    <div class="table-cell">
                        <input type="text" id="initialDeptNote" class="text" value=""/>
                    </div>
                    <div class="table-cell">
                        <a class="button_icon" href="javascript:void(0);" id="addInitialDeptButton" title="Add">
                            <i class="fa fa-plus-circle"></i>
                        </a>
                    </div>
                </div>
            </div>
            <input type="hidden" name="id" value="{$ns.partner->getId()}"/>
            <input id="submitForm" class="button blue" type="submit" value="Save"/>
        </form>
        <div class="table-row" id="partnerInitialDeptTemplate" style="display:none">
            <div class="table-cell">
                <input type="number" class="initialDeptAmount text" />
            </div>
            <div class="table-cell">
                <select class="initialDeptSelectCurrency">               
                    {foreach from=$ns.currencies item=c}
                        <option value="{$c->getId()}" iso="{$c->getIso()}" symbol="{$c->getTemplateChar()}" position="{$c->getSymbolPosition()}" >
                            {$c->getName()} ({$c->getIso()} {$c->getTemplateChar()})</option>
                        {/foreach}
                </select>
            </div>  
            <div class="table-cell">
                <input type="text" class="initialDeptNote text"/>
            </div>
            <div class="table-cell">
                <a class="button_icon removePartnerInitialDept" title="delete">
                    <i class="fa fa-trash-o"></i>
                </a>
            </div>  
            <input type="hidden" name="initialDepts[]"/>
        </div>

    {else}
        Wrong partner!
    {/if}
</div>