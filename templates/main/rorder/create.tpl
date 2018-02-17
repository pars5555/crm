<div class="container rorder--create--container">
    <h1 class="main_title">Create Recipient Order</h1>

    {if isset($ns.error_message)}
        {include file="{ngs cmd=get_template_dir}/main/message.tpl" type="error" content="{$ns.error_message}"} 
    {/if}
    {if isset($ns.success_message)}
        {include file="{ngs cmd=get_template_dir}/main/message.tpl" type="success" content="{$ns.success_message}"} 
    {/if}

    <form class="createRecipientOrder create--form" autocomplete="off" method="post" action="{$SITE_PATH}/dyn/main_rorder/do_create_recipient_order">
        <div class="form-group">
            <label class="label">Date</label>
            {assign order_date $smarty.now|date_format:"%Y-%m-%d %H:%M"}
            {if !empty($ns.req.order_date)}
                {assign order_date $ns.req.order_date}
            {/if}
            <input class="datetimepicker" name ='order_date' type="text" value="{$order_date}"/>
        </div>
        <div class="form-group">
            <label class="label">Recipient</label>
            <select name="recipientId" data-autocomplete="true">
                {if isset($ns.req.recipientId)}
                    {assign selectedRecipientId $ns.req.recipientId}
                {else}
                    {assign selectedRecipientId null}
                {/if}
                <option value="0" >Select Recipient...</option>
                {foreach from=$ns.recipients item=p}
                    <option value="{$p->getId()}" {if isset($selectedRecipientId) && $selectedRecipientId == $p->getId()}selected{/if}>{$p->getName()}</option>
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
