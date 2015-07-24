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
        <a class="button" id="createPaymentButton" href="javascript:void(0);">create</a>
        <a class="button" id="cancelPaymentButton" href="javascript:void(0);">cancel</a>
    </div>
    {include file="{getTemplateDir}/main/payment/payment_create_form.tpl"}

    <div>
        {nest ns=payment_list}
    </div>
</div>
