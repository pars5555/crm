<div class="container payment--create--container">
    <h1 class="main_title">Create PaymentOrder</h1>

    {if isset($ns.error_message)}
        {include file="{ngs cmd=get_template_dir}/main/message.tpl" type="error" content="{$ns.error_message}"} 
    {/if}
    {if isset($ns.success_message)}
        {include file="{ngs cmd=get_template_dir}/main/message.tpl" type="success" content="{$ns.success_message}"} 
    {/if}

    <form class="createPaymentOrder create--form" autocomplete="off" method="post" action="{$SITE_PATH}/dyn/main_payment/do_create_payment">
        <div class="form-group">
            <label class="label">Date</label>
            {assign date $smarty.now|date_format:"%Y-%m-%d %H:%M"}
            {if !empty($ns.req.date)}
                {assign date $ns.req.date}
            {/if}
            <input class="datetimepicker" name ='date' type="text" value="{$date}"/>
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
        <div class="checkbox_container">
            <div class="checkbox f_checkbox">
                <input type="checkbox" name="isExpense" id="isExpenseCheckbox" value="1" {if isset($ns.req.isExpense) && $ns.req.isExpense == 1}checked{/if}/>
            </div>
            <label class="label" for="isExpenseCheckbox">Is Expense</label>
        </div>

        <div class="checkbox_container">
            <div class="checkbox f_checkbox">
                <input type="checkbox" name="paid" id="paidCheckbox" value="1" {if !isset($ns.req.paid) || $ns.req.paid == 1}checked{/if}/>
            </div>
            <label class="checkbox_label label" for="paidCheckbox">Paid</label>
        </div>


        <div class="form-group">
            <label class="label">Note</label>
            <textarea class="text" name="note">{$ns.req.note|default:''}</textarea>
        </div>
        <input class="button blue" type="submit" value="Save"/>
        <input type="hidden" name="signature" id="signature"/>
        <div class="form-group">
            <label class="label">Debt</label>
            <div id="partnerDebtContainer"></div>
            <input type="hidden" id="partnerDebtHidden"/>
        </div>
    </form>
    <div id="signatureContainer" style="width: 500px;border: 1px solid gray;color:#0f60a7">
        <span class="hidden">{$ns.req.signature}</span>
    </div>
    <a class="button clearSignature" >Clear</a>
</div>