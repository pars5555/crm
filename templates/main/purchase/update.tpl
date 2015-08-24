<div class="container purchase--create--container">
    <h1 class="main_title">Create Purchase Order</h1>
    
    {if isset($ns.error_message)}
        {include file="{getTemplateDir}/main/message.tpl" type="error" content="{$ns.error_message}"} 
    {/if}
    {if isset($ns.success_message)}
        {include file="{getTemplateDir}/main/message.tpl" type="success" content="{$ns.success_message}"} 
    {/if}

    {if isset($ns.purchaseOrder)}
        <form class="updatePurchaseOrder create--form" autocomplete="off" method="post" action="{SITE_PATH}/dyn/main_purchase/do_update_purchase_order">
            <div class="form-group">
                <label class="label">Date</label>
                {assign date null}
                {if isset($ns.req.paymentDateYear)}
                    {assign date "`$ns.req.purchaseOrderDateYear`-`$ns.req.purchaseOrderDateMonth`-`$ns.req.purchaseOrderDateDay`"}
                {/if}
                {html_select_date prefix='purchaseOrderDate' start_year=2010 end_year=2020 field_order=YMD time=$date}
                {assign time null}
                {if isset($ns.req.paymentTimeHour)}
                    {assign time "`$ns.req.purchaseOrderTimeHour`:`$ns.req.purchaseOrderTimeMinute`"}
                {/if}
                {html_select_time prefix='purchaseOrderTime' display_seconds=false time=$time}
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
                {assign date null}
                {if !empty($ns.req.paymentDeadlineDateYear) && !empty($ns.req.paymentDeadlineDateMonth) && !empty($ns.req.paymentDeadlineDateDay)}
                    {assign date "`$ns.req.paymentDeadlineDateYear`-`$ns.req.paymentDeadlineDateMonth`-`$ns.req.paymentDeadlineDateDay`"}
                {/if}
                {html_select_date prefix='paymentDeadlineDate' start_year=2010 end_year=2020 field_order=YMD time=$date}
            </div>
            <div class="form-group">
                {if isset($ns.req.isExpense)}
                    {assign selectedIsExpense $ns.req.isExpense}
                {else}
                    {assign selectedIsExpense 0}
                {/if}
                <label class="label" for="isExpenseCheckbox">Is Expense</label>
                <input type="checkbox" name="isExpense" id="isExpenseCheckbox" value="1" {if $selectedIsExpense == 1}checked{/if}/>
            </div>
    </div>

    <div class="form-group">
        <label class="label">Note</label>
        <textarea class="text" name="note">{$ns.req.note|default:''}</textarea>
    </div>
    <input class="button blue" type="submit" value="Save"/>
    <input type="hidden" name="id" value="{$ns.purchaseOrder->getId()}"/>
</form>
{else}
    Wrong Purchase Order!
{/if}
</div>
