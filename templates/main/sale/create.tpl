<div class="container sale--create--container">
    <h1 class="main_title">Create Sale Order</h1>
    
    {if isset($ns.error_message)}
        {include file="{getTemplateDir}/main/message.tpl" type="error" content="{$ns.error_message}"} 
    {/if}
    {if isset($ns.success_message)}
        {include file="{getTemplateDir}/main/message.tpl" type="success" content="{$ns.success_message}"} 
    {/if}

    <form class="createSaleOrder create--form" autocomplete="off" method="post" action="{SITE_PATH}/dyn/main_sale/do_create_sale_order">
        <div class="form-group">
            <label class="label">Date</label>
            {assign date null}
            {if isset($ns.req.paymentDateYear)}
                {assign date "`$ns.req.saleOrderDateYear`-`$ns.req.saleOrderDateMonth`-`$ns.req.saleOrderDateDay`"}
            {/if}
            {html_select_date prefix='saleOrderDate' start_year=2010 end_year=2020 field_order=YMD time=$date}
            {assign time null}
            {if isset($ns.req.paymentTimeHour)}
                {assign time "`$ns.req.saleOrderTimeHour`:`$ns.req.saleOrderTimeMinute`"}
            {/if}
            {html_select_time prefix='saleOrderTime' display_seconds=false time=$time}
        </div>
        <div class="form-group">
            <label class="label">Partner</label>
            <select name="partnerId" data-autocomplete="true" data-no-wrap="true">
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
            <label class="label">Date</label>
            {assign date "+1 month"|date_format:'%Y-%m-%d'}
            {if !empty($ns.req.billingDeadlineDateYear) && !empty($ns.req.billingDeadlineDateMonth) && !empty($ns.req.billingDeadlineDateDay)}
                {assign date "`$ns.req.billingDeadlineDateYear`-`$ns.req.billingDeadlineDateMonth`-`$ns.req.billingDeadlineDateDay`"}
            {/if}
            {html_select_date prefix='billingDeadlineDate' start_year=2010 end_year=2020 field_order=YMD time=$date}
        </div>
</div>
<div class="form-group">
    <label class="label">Note</label>
    <textarea class="text" name="note">{$ns.req.note|default:''}</textarea>
</div>
<input class="button blue" type="submit" value="Save"/>
</form>
</div>
