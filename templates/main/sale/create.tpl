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
    <form class="createSaleOrder" autocomplete="off" method="post" action="{SITE_PATH}/dyn/main/do_create_sale_order">
        <div>
            <label>Date</label>
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
            <label>Note</label>
            <textarea  name="note">{$ns.req.note|default:''}</textarea>
        </div>
        <input type="submit" value="Save"/>
    </form>
</div>
