<div class="container product--list--container">
    <h1 class="main_title">BTC Products</h1>

    {if isset($ns.error_message)}
        {include file="{ngs cmd=get_template_dir}/main/message.tpl" type="error" content="{$ns.error_message}"}
    {/if}
    {if isset($ns.success_message)}
        {include file="{ngs cmd=get_template_dir}/main/message.tpl" type="success" content="{$ns.success_message}"}
    {/if}

    {include file="{ngs cmd=get_template_dir}/main/checkout/list_filters.tpl"}
    {if !empty($ns.changed_orders)}
        <h2>following orders are changed or added, {$ns.changed_orders}</h2>
    {/if}

    {if !empty($ns.preorder_text1)}
        Preorders: <br>
        <h4 style="color:orange">{$ns.preorder_text1}</h4>
    {/if}
    {if !empty($ns.preorder_text2)}
        Cancelled Preorders Items: <br>
        <h2 style="color:red">{$ns.preorder_text2}</h2>
    {/if}

    <div class="main-table">
        <table>
            <tr>
                <th>Actions</th>
                <th>Order Number</th>
                <th>Internal Recipient</th>
                <th>Actual Recipient</th>
                <th>Img</th>
                <th> Qty </th>
                <th> Product Name </th>
                <th> Total </th>
                <th> % </th>
                <th> Buyer </th>
                <th> Status </th>
                <th> Checkout Status </th>
                <th> Note </th>
                <th> S/N </th>
                <th> amazon Order Number </th>
                <th> Tracking Number </th>
                <th> hidden At </th>
                <th> created </th>
            </tr>

            {foreach from=$ns.orders item=order}
                <tr {if $order->getHidden()==1} style="background: lightgray;"{else}{if $order->isDelayed()} style="background: orange;"{/if}{if $order->getProblematic() == 1 && $order->getProblemSolved() == 0} style="background: orange;"{/if}{/if} data-type="checkout" data-id="{$order->getId()}">
                    <td>
                        <a href="{$SITE_PATH}/btc/{$order->getId()}" class="link" target="_blank">{$order->getId()}</a><br/>
                        {$order->getCheckoutOrderMetadataProperty('name')}


                        <a href="javascript:void(0);" {if $ns.problematic == 1}style="color: red"{/if} id="problematic_{$order->getId()}" class="fa fa-exclamation-triangle fa-1x f_problematic right" data-id='{$order->getId()}'></a>
                        <br/>
                        {if $order->getUnreadMessages() > 0}
                            <span class="fa fa-envelope" style="color: red">{$order->getUnreadMessages()}</span>
                        {/if}
                        {assign notifications $order->getCheckoutOrderMetadataProperty('notifications')}
                        {assign notificationText ''}
                        {if !empty($notifications)}

                            {foreach from=$notifications item=notification}
                                {assign notificationText "`$notificationText` `$notification->body` \r\n"}
                            {/foreach}
                            <span class="fa fa-2x fa-commenting" title="{$notificationText}" style="color: red">{count($notifications)}</span>
                        {/if}
                        <br/>

                        {if $ns.problematic == 1 || $order->isDelayed()}
                            <a href="javascript:void(0);" id="problem_solved_{$order->getId()}" class="fa fa-check-circle fa-2x f_problem_solved" data-id='{$order->getId()}'></a>
                        {/if}
                        {if isset($ns.attachments[$order->getId()]) || isset($ns.checkout_attachments[$order->getId()])}
                            <img src="{$SITE_PATH}/img/attachment.png" width="32"/>
                        {/if}
                    </td>

                    <td>
                        <a class="link" target="_black" href="https://purse.io/order/{$order->getOrderNumber()}" > {$order->getOrderNumber()} </a>
                        <br/>
                        <span {if $order->getShippingType()=='standard'}style='color:red'{/if} >{$order->getShippingType()}</span>
                    </td>
                    <td> 
                        {if not $order->getRecipientName()}
                            <a href="javascript:void(0);" class="fa fa-refresh f_refresh_recipient" data-id='{$order->getId()}'></a>
                        {/if}
                        {$order->getRecipientName()} {$order->getUnitAddress()} ({$order->getAccountName()|replace:'purse_':''})
                    </td>
                    <td class="f_editable_cell" data-field-name="checkout_customer_unit_address"> 
                        {$order->getCheckoutCustomerName()} {$order->getCheckoutCustomerUnitAddress()}
                        {$order->getCheckoutOrderMetadataProperty('user_object->email')}
                        <a class="button blue f_confirm_order" data-id="{$order->getId()}">Confirm</a>
                    </td>

                    <td> 
                        <img src="{$order->getImageUrl()}" width="100"/>
                        <a target="_blank" href="{$order->getCheckoutOrderProductLink()}"><img src="{$SITE_PATH}/img/link.png" width="32"/></a> </td>

                    <td > {$order->getQuantity()} </td>
                    <td {if $order->getExternal() == 1}class="f_editable_cell"{/if} data-field-name="product_name">
                        {if $order->getAmazonOrderNumber()|count_characters > 5}
                            <a class="link " target="_black" href="https://www.amazon.com/returns/cart?orderId={$order->getAmazonOrderNumber()}">{$order->getProductName()}</a>
                        {else}
                            {$order->getProductName()}
                        {/if}
                    </td>
                    <td> {$order->getAmazonTotal()} <br> ({$order->getCheckoutOrderMetadataProperty('order_total_amount')})</td>
                    <td> {$order->getDiscount()} </td>
                    <td style="max-width: 70px;word-wrap: break-word"> {$order->getBuyerName()} </td>
                    <td> {$order->getStatus()} </td>
                    <td class="f_selectable_cell" data-value="{$order->getCheckoutOrderStatus()}" data-field-name="checkout_order_status" data-template-select-id="checkout_order_status_select"> {$ns.checkout_order_statuses[$order->getCheckoutOrderStatus()]} </td>
                    <td class="table-cell f_editable_cell" data-field-name="note" data-type="richtext" style="min-width: 100px"> {$order->getNote()} </td>
                    <td class="table-cell f_editable_cell" data-field-name="serial_number"  > {$order->getSerialNumber()} </td>
                    <td class="table-cell f_editable_cell"  data-field-name="amazon_order_number">
                        <a class="link" target="_black" href="https://www.amazon.com/progress-tracker/package/ref=oh_aui_hz_st_btn?_encoding=UTF8&itemId=jnljnvjtqlspon&orderId={$order->getAmazonOrderNumber()}" > {$order->getAmazonOrderNumber()} </a>
                        <br/>
                        {$order->getAmazonPrimaryStatusText()}
                    </td>
                    <td class="f_editable_cell" data-field-name="tracking_number" >
                        {$order->getTrackingNumber()}
                    </td>
                    <td> 
                        {$order->getHiddenAt()} 
                        {if isset($ns.btc_purchase_orders[$order->getId()])}
                            <br/>
                            <br/>
                            <a target="_blank" href="{$SITE_PATH}/purchase/{$ns.btc_purchase_orders[$order->getId()]}">
                                <span>PO#{$ns.btc_purchase_orders[$order->getId()]} </span>
                            </a>
                        {/if}
                    </td>
                    <td> {$order->getCreatedAt()} </td>
                </tr>
            {/foreach}
        </table>
    </div>
</div>

<div id="confirm_order" class="modal modal-large">
    <div class="modal-container">
        <div class="modal-inner-container" >
            <span class="modal-close">
                <span class="close-icon1"></span>
                <span class="close-icon2"></span>
            </span>
            <h1 class="modal-headline">Confirm</h1>
            <h4 class="modal-sub-headline">You you sure you want to confirm?</h4>


            <input type="hidden" id="confirming_order_id" value=""/>
            <div class="modal-content observers-detail-modal-content">
                <a class="button blue" id="confirm_checkout_order_btn">Confirm</a>
            </div>

        </div>
    </div>
</div>
<select id='checkout_order_status_select' class="hidden" style="width: 120px" >
    {foreach from=$ns.checkout_order_statuses key=status_id item=status_name}
        <option value="{$status_id}">{$status_name}</option>
    {/foreach}
</select>

