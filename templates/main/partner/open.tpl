<div class="container partner--open--container">
    <h1>Partner View</h1>
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
    {if isset($ns.partner)}
        <div>
            id: {$ns.partner->getId()}
        </div>
        <div>
            name: {$ns.partner->getName()}
        </div>
        <div>
            email: {$ns.partner->getEmail()}
        </div>
        <div>
            address: {$ns.partner->getAddress()}
        </div>
        <div>
            phone: {$ns.partner->getPhone()}
        </div>



        <div>
            Sale Orders : <a class="table-cell link" href="{SITE_PATH}/sale/list?prt={$ns.partner->getId()}"> {$ns.partnerSaleOrders|@count}</a>
        </div>

        <div>
            Purchase Orders : <a class="table-cell link" href="{SITE_PATH}/purchase/list?prt={$ns.partner->getId()}">{$ns.partnerPurchaseOrders|@count}</a>
        </div>
        <div>
            Payment Transactions: <a class="table-cell link" href="{SITE_PATH}/payment/list?prt={$ns.partner->getId()}">{$ns.partnerPaymentTransactions|@count}</a>
        </div>
        <div>
            Billing Transactions: <a class="table-cell link" href="{SITE_PATH}/billing/list?prt={$ns.partner->getId()}">{$ns.partnerBillingTransactions|@count}</a>
        </div>
        <div>
            {if !empty($partnerDept)}
                {foreach from=$partnerDept key=currencyId item=amount}
                    <span style="white-space-collapse: discard;">
                        {assign currencyDto $ns.currencies[$currencyId]}
                        {if $currencyDto->getSymbolPosition() == 'left'}
                            {$currencyDto->getTemplateChar()}
                        {/if}
                        {$amount}
                        {if $currencyDto->getSymbolPosition() == 'right'}
                            {$currencyDto->getTemplateChar()}
                        {/if}
                    </span>
                {/foreach}
            {/if}
        </div>
        <form action="{SITE_PATH}/dyn/main_partner/do_delete_partner">
            <input type="hidden" name="id" value="{$ns.partner->getId()}"/>
            <a id="deletePartnerButton" href="javascript:void(0);">Delete</a>
        </form>
    {else}
        Wrong partner!
    {/if}
</div>
