<div class="container preorder--create--container">
    <h1 class="main_title">Create Preorder</h1>

    {if isset($ns.error_message)}
        {include file="{ngs cmd=get_template_dir}/main/message.tpl" type="error" content="{$ns.error_message}"}
    {/if}
    {if isset($ns.success_message)}
        {include file="{ngs cmd=get_template_dir}/main/message.tpl" type="success" content="{$ns.success_message}"}
    {/if}

    {if isset($ns.preorder)}
        <form class="updatePreorder create--form" autocomplete="off" method="post" action="{$SITE_PATH}/dyn/main_preorder/do_update_preorder">
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
                <label class="label">Payment Deadline</label>
                {assign payment_deadline "+1 month"|date_format:'%Y-%m-%d'}
                {if !empty($ns.req.payment_deadline)}
                    {assign payment_deadline $ns.req.payment_deadline}
                {/if}
                <input class="datepicker" name ='payment_deadline' type="text" value="{$payment_deadline}"/>
            </div>


            <div class="form-group">
                <label class="label">BTC Order Numbers</label>
                <textarea placeholder="eg. 7, 128, 8, ..." class="text" name="purse_order_ids">{$ns.req.purse_order_ids|default:''}</textarea>
            </div>
            <div class="checkbox_container">
                {if isset($ns.req.purchased)}
                    {assign selectedPurchased $ns.req.purchased}
                {else}
                    {assign selectedPurchased 0}
                {/if}
                <div class="checkbox f_checkbox">
                    <input type="checkbox" name="purchased" id="purchasedCheckbox" value="1" {if $selectedPurchased == 1}checked{/if}/>
                </div>
                <label class="checkbox_label label" for="purchasedCheckbox">Purchased</label>
            </div>

            <div class="checkbox_container">
                {if isset($ns.req.finished)}
                    {assign selectedFinished $ns.req.finished}
                {else}
                    {assign selectedFinished 0}
                {/if}
                <div class="checkbox f_checkbox">
                    <input type="checkbox" name="finished" id="finishedCheckbox" value="1" {if $selectedFinished == 1}checked{/if}/>
                </div>
                <label class="checkbox_label label" for="finishedCheckbox">Finished</label>
            </div>

            <div class="form-group">
                <label class="label">Note</label>
                <textarea class="text" name="note">{$ns.req.note|default:''}</textarea>
            </div>
            <input class="button blue" type="submit" value="Save"/>
            <input type="hidden" name="id" value="{$ns.preorder->getId()}"/>
        </form>
    {else}
        Wrong Preorder!
    {/if}
</div>
