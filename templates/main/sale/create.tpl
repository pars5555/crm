<div class="container sale--create--container">
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
    <form class="createSaleOrder create--form" autocomplete="off" method="post" action="{SITE_PATH}/dyn/main/do_create_sale_order">
        <div class="form-group">
            <label class="label">Date</label>
            {assign date null}
            {if isset($ns.req.paymentDateYear)}
                {assign date "`$ns.req.saleOrderDateYear`-`$ns.req.saleOrderDateMonth`-`$ns.req.saleOrderDateDay`"}
            {/if}
            {html_select_date prefix='saleOrderDate' start_year=2010 end_year=2020 time=$date}
            {assign time null}
            {if isset($ns.req.paymentTimeHour)}
                {assign time "`$ns.req.saleOrderTimeHour`:`$ns.req.saleOrderTimeMinute`"}
            {/if}
            {html_select_time prefix='saleOrderTime' display_seconds=false time=$time}
        </div>
        <div class="form-group">
            <label class="label">Partner</label>
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
        <div class="form-group">
            <label class="label">Note</label>
            <textarea class="text" name="note">{$ns.req.note|default:''}</textarea>
        </div>
        <input class="button blue" type="submit" value="Save"/>
    </form>
</div>
