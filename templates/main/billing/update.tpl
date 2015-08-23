<div class="container billing--create--container">
    <h1 class="main_title">Create Billing Order</h1>
    
    {if isset($ns.error_message)}
        {include file="{getTemplateDir}/main/message.tpl" type="error" content="{$ns.error_message}"} 
    {/if}
    {if isset($ns.success_message)}
        {include file="{getTemplateDir}/main/message.tpl" type="success" content="{$ns.success_message}"} 
    {/if}

    {if isset($ns.billingOrder)}
        <form class="updateBillingOrder create--form" autocomplete="off" method="post" action="{SITE_PATH}/dyn/main_billing/do_update_billing">
            <div class="form-group">
                <label class="label">Billing Date</label>
                {assign date null}
                {if isset($ns.req.billingDateYear)}
                    {assign date "`$ns.req.billingDateYear`-`$ns.req.billingDateMonth`-`$ns.req.billingDateDay`"}
                {/if}
                {html_select_date prefix='billingDate' start_year=2010 end_year=2020 field_order=YMD time=$date}
                {assign time null}
                {if isset($ns.req.billingTimeHour)}
                    {assign time "`$ns.req.billingTimeHour`:`$ns.req.billingTimeMinute`"}
                {/if}
                {html_select_time prefix='billingTime' display_seconds=false time=$time}
            </div>
            <div class="form-group">
                <label class="label">Partner</label>
                <select name="partnerId" data-autocomplete="true">
                    {if isset($ns.req.partnerId)}
                        {assign selectedPartnerId $ns.req.partnerId}
                    {else}
                        {assign selectedPartnerId null}
                    {/if}
                    <option value="0">Select Partner...</option>
                    {foreach from=$ns.partners item=p}
                        <option value="{$p->getId()}" {if isset($selectedPartnerId) && $selectedPartnerId == $p->getId()}selected{/if}>{$p->getName()}</option>
                    {/foreach}
                </select>
            </div>
            <div class="form-group">
                <label class="label">Billing Method</label>
                <select name="billingMethodId">
                    {if isset($ns.req.billingMethodId)}
                        {assign selectedPaymentMethodId $ns.req.billingMethodId}
                    {else}
                        {assign selectedPaymentMethodId $ns.defaultPaymentMethodId}
                    {/if}                
                    {foreach from=$ns.billing_methods item=pm}
                        <option {if $pm->getId() == $selectedPaymentMethodId}selected{/if}
                                                                             value="{$pm->getId()}">{$pm->getName()}</option>
                    {/foreach}
                </select>
            </div>
            <div class="form-group">
                <label class="label">Currency</label>
                <select name="currencyId">
                    {if isset($ns.req.currencyId)}
                        {assign selectedCurrencyId $ns.req.currencyId}
                    {else}
                        {assign selectedCurrencyId $ns.defaultCurrencyId}
                    {/if}
                    {foreach from=$ns.currencies item=c}
                        <option {if $c->getId() == $selectedCurrencyId}selected{/if}
                                                                       value="{$c->getId()}">{$c->getName()} ({$c->getIso()} {$c->getTemplateChar()})</option>
                    {/foreach}
                </select>
            </div>
            <div class="form-group">
                <label class="label">Amount</label>
                <input class="text" type="number" step="0.01" name="amount" value="{$ns.req.amount|default:''}"/>
            </div>
            <div class="form-group">
                <label class="label">Note</label>
                <textarea class="text" name="note">{$ns.req.note|default:''}</textarea>
            </div>
            <input class="button blue" type="submit" value="Save"/>
            <input type="hidden" name="id" value="{$ns.billingOrder->getId()}"/>
        </form>
    {else}
        Wrong Payment Order!
    {/if}
</div>