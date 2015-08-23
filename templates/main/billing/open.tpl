<div class="container billing--open--container">
    <h1>Billing Order View</h1>
    
    {if isset($ns.error_message)}
        {include file="{getTemplateDir}/main/message.tpl" type="error" content="{$ns.error_message}"} 
    {/if}
    {if isset($ns.success_message)}
        {include file="{getTemplateDir}/main/message.tpl" type="success" content="{$ns.success_message}"} 
    {/if}

    {if isset($ns.billing)}
        <div class="table_striped table_striped_simple">
            <div class="table-row">
                <span class="table-cell">
                    id :
                </span>
                <span class="table-cell">
                    {$ns.billing->getId()}
                </span>
            </div>
            <div class="table-row">
                <span class="table-cell">
                    date :
                </span>
                <span class="table-cell">
                    {$ns.billing->getDate()}
                </span>
            </div>
            <div class="table-row">
                <span class="table-cell">
                    billing method :
                </span>
                <span class="table-cell">
                    {$ns.lm->getPhrase($ns.billing->getPaymentMethodDto()->getTranslationId())}
                </span>
            </div>
            <div class="table-row">
                <span class="table-cell">
                    Partner :
                </span>
                <span class="table-cell">
                    {$ns.billing->getPartnerDto()->getName()}
                </span>
            </div>
            <div class="table-row">
                <span class="table-cell">
                    Amount : 
                </span>
                <span class="table-cell">
                    {if $billing->getCurrencyDto()->getSymbolPosition() == 'left'}
                        {$billing->getCurrencyDto()->getTemplateChar()}
                    {/if}
                    {-$billing->getAmount()}
                    {if $billing->getCurrencyDto()->getSymbolPosition() == 'right'}
                        {$billing->getCurrencyDto()->getTemplateChar()}
                    {/if}
                </span>
            </div>
            <div class="table-row">
                <span class="table-cell">
                    Note :
                </span>
                <span class="table-cell">
                    {$ns.billing->getNote()}
                </span>
            </div>
        </div>
        {if $billing->getCancelled() == 0}
            <form action="{SITE_PATH}/dyn/main_billing/do_cancel_billing">
                <input type="hidden" name="id" value="{$ns.billing->getId()}"/>
                <div class="form-group">
                    <label class="label">Note :</label>
                    <textarea class="text" name="note"></textarea>
                </div>
                <a class="button blue" id="cancelBillingButton" href="javascript:void(0);">Cancel</a>
            </form>
        {else}
            <a class="button blue"  href="{SITE_PATH}/dyn/main_billing/do_restore_billing?id={$ns.billing->getId()}">
                <span>Restore</span>
            </a>
        {/if}
    {/if}
</div>
