<div class="container sale--create--container">
    <h1 class="main_title">Create Sale Order</h1>

    {if isset($ns.error_message)}
        {include file="{ngs cmd=get_template_dir}/main/message.tpl" type="error" content="{$ns.error_message}"} 
    {/if}
    {if isset($ns.success_message)}
        {include file="{ngs cmd=get_template_dir}/main/message.tpl" type="success" content="{$ns.success_message}"} 
    {/if}

    <form class="createSaleOrder create--form" autocomplete="off" method="post" action="{$SITE_PATH}/dyn/main_sale/do_create_sale_order">
        <div class="form-group">
            <label class="label">Date</label>
            {assign order_date $smarty.now|date_format:"%Y-%m-%d %H:%M"}
            {if !empty($ns.req.order_date)}
                {assign order_date $ns.req.order_date}
            {/if}
            <input class="datetimepicker" name ='order_date' type="text" value="{$order_date}"/>
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
            <label class="label">Billing Deadline</label>
            {assign billing_deadline "+1 month"|date_format:'%Y-%m-%d'}
            {if !empty($ns.req.billing_deadline)}
                {assign billing_deadline $ns.req.billing_deadline}
            {/if}
            <input class="datepicker" name ='billing_deadline' type="text" value="{$billing_deadline}"/>
        </div>
        <div class="form-group">
            <label class="label" for="isExpenseCheckbox">Is Expense</label>
            <input type="checkbox" name="isExpense" id="isExpenseCheckbox" value="1" {if isset($ns.req.isExpense) && $ns.req.isExpense == 1}checked{/if}/>
        </div>
        <div class="form-group wide">
            <label class="label">Note</label>
            <textarea class="text" name="note">{$ns.req.note|default:''}</textarea>
        </div>
        <input class="button blue" type="submit" value="Save"/>
        <input type="hidden" name="poid" value="{$ns.purchase_order_id}"/>
    </form>
</div>
