<div class="container payment--list--container">
    <h1 class="main_title">Payment Orders</h1>

    {if isset($ns.error_message)}
        {include file="{ngs cmd=get_template_dir}/main/message.tpl" type="error" content="{$ns.error_message}"} 
    {/if}
    {if isset($ns.success_message)}
        {include file="{ngs cmd=get_template_dir}/main/message.tpl" type="success" content="{$ns.success_message}"} 
    {/if}

    {include file="{ngs cmd=get_template_dir}/main/payment/list_filters.tpl"}
    <a  href="{$SITE_PATH}/payment/create"><img src="{$SITE_PATH}/img/add.png"/></a>
    <div class="table_striped">
        <div class="table_header_group">
            <span class="table-cell"> ID </span>
            <span class="table-cell"> DATE </span>
            <span class="table-cell"> Partner </span>
            <span class="table-cell"> Payment Method </span>
            <span class="table-cell"> Amount </span>
            <span class="table-cell"> Note </span>
            <span class="table-cell"> Expense </span>
            <span class="table-cell"> Paid </span>
            <span class="table-cell"> View </span>
            <span class="table-cell"> Edit </span>
            <span class="table-cell"> Delete </span>
        </div> 
        {foreach from=$ns.payments item=payment}
            <div class="table-row" {if $payment->getCancelled() == 1}style="color: gray"{/if} href="{$SITE_PATH}/payment/{$payment->getId()}">
                <a class="table-cell" href="{$SITE_PATH}/payment/{$payment->getId()}">
                    <span>{$payment->getId()} </span>
                </a>
                <span class="table-cell"> {$payment->getDate()} </span>
                <span class="table-cell"> {$payment->getPartnerDto()->getName()} </span>
                <span class="table-cell"> {$payment->getPaymentMethodDto()->getName()} </span>
                <span class="table-cell"> 
                    {if $payment->getCurrencyDto()->getSymbolPosition() == 'left'}
                        {$payment->getCurrencyDto()->getTemplateChar()}
                    {/if}
                    {$payment->getAmount()}
                    {if $payment->getCurrencyDto()->getSymbolPosition() == 'right'}
                        {$payment->getCurrencyDto()->getTemplateChar()}
                    {/if}
                </span>
                <span class="table-cell"> {$payment->getNote()} </span>



                <span class=" table-cell view_item button_icon">
                    {if $payment->getIsExpense() == 1}
                        <i class="fa fa-exclamation-triangle"></i>
                    {else}
                    {/if}
                </span>
                <span class=" table-cell view_item button_icon">
                    {if $payment->getPaid() == 1}
                        <i class="fa fa-check"></i>
                    {else}
                        <i class="fa fa-times"></i>
                    {/if}
                </span>

                <a class="table-cell view_item" href="{$SITE_PATH}/payment/{$payment->getId()}">
                    <span class="button_icon" title="View">
                        <i class="fa fa-eye"></i>
                    </span>
                </a>
                <a class="table-cell view_item" href="{$SITE_PATH}/payment/edit/{$payment->getId()}">
                    <span class="button_icon" title="Edit">
                        <i class="fa fa-pencil"></i>
                    </span>
                </a>
                {if $payment->getCancelled() == 1}
                    <a class="table-cell view_item deletePayment"  href="{$SITE_PATH}/dyn/main_payment/do_delete_payment?id={$payment->getId()}">
                        <span class="button_icon" title="delete">
                            <i class="fa fa-trash-o"></i>
                        </span>
                    </a>
                {else}
                    <a class="table-cell" href="javascript:void(0);">
                    </a>
                {/if}
            </div>
        {/foreach}


    </div>



</div>