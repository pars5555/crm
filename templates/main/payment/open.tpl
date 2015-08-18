<div class="container payment--open--container">
    <h1>Payment Order View</h1>
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

    {if isset($ns.payment)}
        <div class="table_striped table_striped_simple">
            <div class="table-row">
                <span class="table-cell">
                    id :
                </span>
                <span class="table-cell">
                    {$ns.payment->getId()}
                </span>
            </div>
            <div class="table-row">
                <span class="table-cell">
                    date :
                </span>
                <span class="table-cell">
                    {$ns.payment->getDate()}
                </span>
            </div>
            <div class="table-row">
                <span class="table-cell">
                    payment method :
                </span>
                <span class="table-cell">
                    {$ns.lm->getPhrase($ns.payment->getPaymentMethodDto()->getTranslationId())}
                </span>
            </div>
            <div class="table-row">
                <span class="table-cell">
                    Partner :
                </span>
                <span class="table-cell">
                    {$ns.payment->getPartnerDto()->getName()}
                </span>
            </div>
            <div class="table-row">
                <span class="table-cell">
                    Amount : 
                </span>
                <span class="table-cell">
                    {if $payment->getCurrencyDto()->getSymbolPosition() == 'left'}
                        {$payment->getCurrencyDto()->getTemplateChar()}
                    {/if}
                    {$payment->getAmount()}
                    {if $payment->getCurrencyDto()->getSymbolPosition() == 'right'}
                        {$payment->getCurrencyDto()->getTemplateChar()}
                    {/if}
                </span>
            </div>
            <div class="table-row">
                <span class="table-cell">
                    Note :
                </span>
                <span class="table-cell">
                    {$ns.payment->getNote()}
                </span>
            </div>
        </div>
        {if $payment->getCancelled() == 0}
            <form action="{SITE_PATH}/dyn/main_payment/do_cancel_payment">
                <input type="hidden" name="id" value="{$ns.payment->getId()}"/>
                <div class="form-group">
                    <label class="label">Note</label>
                    <textarea class="text" name="note"></textarea>
                </div>
                <a class="button blue" id="cancelPaymentButton" href="javascript:void(0);">Cancel</a>
            </form>
        {else}
            <a class="button blue" href="{SITE_PATH}/dyn/main_payment/do_restore_payment?id={$ns.payment->getId()}">
                <span>Restore</span>
            </a>
        {/if}
    {/if}
</div>
