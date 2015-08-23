<div class="container payment--create--container">
    <h1 class="main_title">Create PaymentOrder</h1>
    
    {if isset($ns.error_message)}
        {include file="{getTemplateDir}/main/message.tpl" type="error" content="{$ns.error_message}"} 
    {/if}
    {if isset($ns.success_message)}
        {include file="{getTemplateDir}/main/message.tpl" type="success" content="{$ns.success_message}"} 
    {/if}

    <form class="createPaymentOrder create--form" autocomplete="off" method="post" action="{SITE_PATH}/dyn/main_payment/do_create_payment">
        <div class="form-group">
            <label class="label">Payment Date</label>
            {assign date null}
            {if isset($ns.req.paymentDateYear)}
                {assign date "`$ns.req.paymentDateYear`-`$ns.req.paymentDateMonth`-`$ns.req.paymentDateDay`"}
            {/if}
            {html_select_date prefix='paymentDate' start_year=2010 end_year=2020 field_order=YMD time=$date}
            {assign time null}
            {if isset($ns.req.paymentTimeHour)}
                {assign time "`$ns.req.paymentTimeHour`:`$ns.req.paymentTimeMinute`"}
            {/if}
            {html_select_time prefix='paymentTime' display_seconds=false time=$time}
        </div>
        <div class="form-group">
            <label class="label">Partner</label>
            <select name="partnerId" data-autocomplete="true">
                {if isset($ns.req.partnerId)}
                    {assign selectedPartnerId $ns.req.partnerId}
                {else}
                    {assign selectedPartnerId null}
                {/if}
                <option value="0" >Select Partner...</option>
                {foreach from=$ns.partners item=p}
                    <option value="{$p->getId()}" {if isset($selectedPartnerId) && $selectedPartnerId == $p->getId()}selected{/if}>{$p->getName()}</option>
                {/foreach}
            </select>
        </div>
        <div class="form-group">
            <label class="label">Payment Method</label>
            <select name="paymentMethodId">
                {if isset($ns.req.paymentMethodId)}
                    {assign selectedPaymentMethodId $ns.req.paymentMethodId}
                {else}
                    {assign selectedPaymentMethodId $ns.defaultPaymentMethodId}
                {/if}
                {foreach from=$ns.payment_methods item=pm}
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

    </form>
</div>