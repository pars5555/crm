<div class="container partner--open--container">
    <h1 class="main_title">Partner View</h1>

    {if isset($ns.error_message)}
        {include file="{ngs cmd=get_template_dir}/main/message.tpl" type="error" content="{$ns.error_message}"}
    {/if}
    {if isset($ns.success_message)}
        {include file="{ngs cmd=get_template_dir}/main/message.tpl" type="success" content="{$ns.success_message}"}
    {/if}

    {if isset($ns.partner)}

        <div class="main-table">
            <table style="width: auto;" class="margin-auto">
                <tr>
                    <td>
                        id :
                    </td>
                    <td>
                        {$ns.partner->getId()}
                    </td>
                </tr>
                <tr>
                    <td>
                        name :
                    </td>
                    <td>
                        {$ns.partner->getName()}
                    </td>
                </tr>
                <tr>
                    <td>
                        email :
                    </td>
                    <td>
                        {$ns.partner->getEmail()}
                    </td>
                </tr>
                <tr>
                    <td>
                        address :
                    </td>
                    <td>
                        {$ns.partner->getAddress()}
                    </td>
                </tr>
                <tr>
                    <td>
                        phone :
                    </td>
                    <td>
                        {$ns.partner->getPhone()}
                    </td>
                </tr>
                <tr>
                    <td>
                        Sale Orders :
                    </td>
                    <td>
                        <a class="link" href="{$SITE_PATH}/sale/list?prt={$ns.partner->getId()}">
                            {$ns.partnerSaleOrders|@count}
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>
                        Purchase Orders :
                    </td>
                    <td>
                        <a class="link" href="{$SITE_PATH}/purchase/list?prt={$ns.partner->getId()}">
                            {$ns.partnerPurchaseOrders|@count}
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>
                        Payment Transactions:
                    </td>
                    <td>
                        <a class="link" href="{$SITE_PATH}/payment/list?prt={$ns.partner->getId()}">
                            {$ns.partnerPaymentTransactions|@count}
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>
                        Billing Transactions:
                    </td>
                    <td>
                        <a class="link" href="{$SITE_PATH}/billing/list?prt={$ns.partner->getId()}">
                            {$ns.partnerBillingTransactions|@count}
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>
                        All Deals:
                    </td>
                    <td>
                        <a class="link" href="{$SITE_PATH}/partner/all/{$ns.partner->getId()}">
                            <i class="fa fa-calendar"></i>
                        </a>
                    </td>
                </tr>
                {if !empty($partnerDebt)}
                    <tr>
                        {foreach from=$partnerDebt key=currencyId item=amount}
                            <td>
                                Amount
                            </td>
                            <td>
                                {assign currencyDto $ns.currencies[$currencyId]}
                                {if $currencyDto->getSymbolPosition() == 'left'}
                                    {$currencyDto->getTemplateChar()}
                                {/if}
                                {$amount}
                                {if $currencyDto->getSymbolPosition() == 'right'}
                                    {$currencyDto->getTemplateChar()}
                                {/if}
                            </td>
                        {/foreach}
                    </tr>
                {/if}
            </table>
        </div>
        <form action="{$SITE_PATH}/dyn/main_partner/do_delete_partner">
            <input type="hidden" name="id" value="{$ns.partner->getId()}"/>
            <a class="button blue" id="deletePartnerButton" href="javascript:void(0);">Delete</a>
        </form>
    {else}
        Wrong partner!
    {/if}
</div>
    
{include file="{ngs cmd=get_template_dir}/main/util/attachments.tpl"} 

<form id="upload_attachment_form" target="upload_target" enctype="multipart/form-data" method="post" action="{$SITE_PATH}/dyn/attachment/do_upload" autocomplete="off">
    <a class="button blue" id="select_attachment_button" >select attachment...</a>
    <input type="hidden" name="entity_id" value="{$ns.partner->getId()}"/>
    <input type="hidden" name="entity_name" value="partner"/>
    <input type="hidden" name="partner_id" value="{$ns.partner->getId()}"/>
    <input id="file_input" name="file" type="file" style="display:none" />
</form>
<iframe id="upload_target" name="upload_target" style="width:0;height:0;border:0px solid #fff;display: none;" ></iframe>
