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
    <div>
        <a class="button" href="javascript:void(0);">create</a>
        <a class="button" href="javascript:void(0);">cancel</a>
    </div>
    <form class="createSaleOrder" autocomplete="off" method="post" action="{$SITE_PATH}/dyn/main/do_create_payment">
        <div>
            <label>Payment Date</label>
            {assign date null}
            {if isset($ns.req.paymentDateYear)}
                {assign date "`$ns.req.paymentDateYear`-`$ns.req.paymentDateMonth`-`$ns.req.paymentDateDay`"}
            {/if}
            {html_select_date prefix='paymentDate' start_year=2010 end_year=2020 time=$date}
            {assign time null}
            {if isset($ns.req.paymentTimeHour)}
                {assign time "`$ns.req.paymentTimeHour`:`$ns.req.paymentTimeMinute`"}
            {/if}
            {html_select_time prefix='paymentTime' display_seconds=false time=$time}
        </div>
        <div>
            <label>Partner</label>
            <select name="partnerId">
                {if isset($ns.req.partnerId)}
                    {assign selectedPartnerId $ns.req.partnerId}
                {else}
                    {assign selectedPartnerId null}
                {/if}
                {foreach from=$ns.partners item=p}
                    <option value="{$p->getId()}" {if isset($selectedPartnerId) && $selectedPartnerId == $p->getId()}selected{/if}>{$p->getName()}</option>
                {/foreach}
            </select>
        </div>
        <div>
            <label>Payment Method</label>
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
        <div>
            <label>Currency</label>
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
        <div>
            <label>Amount</label>
            <input type="number" step="0.01" name="amount"/>
        </div>
        <div>
            <label>Note</label>
            <textarea  name="note"></textarea>
        </div>
        <input type="submit" value="Create"/>

    </form>

    <div>
        {nest ns=payment_list}
    </div>
</div>
