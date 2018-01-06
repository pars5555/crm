<div class="container partner--open--container">
    <h1 class="main_title">Partner View</h1>
    
    {if isset($ns.error_message)}
        {include file="{ngs cmd=get_template_dir}/main/message.tpl" type="error" content="{$ns.error_message}"} 
    {/if}
    {if isset($ns.success_message)}
        {include file="{ngs cmd=get_template_dir}/main/message.tpl" type="success" content="{$ns.success_message}"} 
    {/if}

    {if isset($ns.partner)}
        <div class="table_striped table_striped_simple">
            <div class="table-row">
                <span class="table-cell">
                    id :
                </span>
                <span class="table-cell">
                    {$ns.partner->getId()}
                </span>
            </div>
            <div class="table-row">
                <span class="table-cell">
                    name :
                </span>
                <span class="table-cell">
                    {$ns.partner->getName()}
                </span>
            </div>
            <div class="table-row">
                <span class="table-cell">
                    email :
                </span>
                <span class="table-cell">
                    {$ns.partner->getEmail()}
                </span>
            </div>
            <div class="table-row">
                <span class="table-cell">
                    address :
                </span>
                <span class="table-cell">
                    {$ns.partner->getAddress()}
                </span>
            </div>
            <div class="table-row">
                <span class="table-cell">
                    phone :
                </span>
                <span class="table-cell">
                    {$ns.partner->getPhone()}
                </span>
            </div>
            <div class="table-row">
                <span class="table-cell">
                    Sale Orders :
                </span>
                <a class="table-cell link" href="{$SITE_PATH}/sale/list?prt={$ns.partner->getId()}">
                    {$ns.partnerSaleOrders|@count}
                </a>
            </div>
            <div class="table-row">
                <span class="table-cell">
                    Purchase Orders :
                </span>
                <a class="table-cell link" href="{$SITE_PATH}/purchase/list?prt={$ns.partner->getId()}">
                    {$ns.partnerPurchaseOrders|@count}
                </a>
            </div>
            <div class="table-row">
                <span class="table-cell">
                    Payment Transactions:
                </span>
                <a class="table-cell link" href="{$SITE_PATH}/payment/list?prt={$ns.partner->getId()}">
                    {$ns.partnerPaymentTransactions|@count}
                </a>
            </div>
            <div class="table-row">
                <span class="table-cell">
                    Billing Transactions:
                </span>
                <a class="table-cell link" href="{$SITE_PATH}/billing/list?prt={$ns.partner->getId()}">
                    {$ns.partnerBillingTransactions|@count}
                </a>
            </div>
            <div class="table-row">
                <span class="table-cell">
                    All Deals:
                </span>
                <a class="table-cell link" href="{$SITE_PATH}/partner/all/{$ns.partner->getId()}">
                    <i class="fa fa-calendar"></i>
                </a>
            </div>
            {if !empty($partnerDebt)}
                <div class="table-row" style="white-space-collapse: discard;">
                    {foreach from=$partnerDebt key=currencyId item=amount}
                        <span class="table-cell">
                            Amount
                        </span>
                        <span class="table-cell">
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
                </div>
            {/if}
        </div>
        <form action="{$SITE_PATH}/dyn/main_partner/do_delete_partner">
            <input type="hidden" name="id" value="{$ns.partner->getId()}"/>
            <a class="button blue" id="deletePartnerButton" href="javascript:void(0);">Delete</a>
        </form>
    {else}
        Wrong partner!
    {/if}
</div>
