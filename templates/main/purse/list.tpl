<div class="container product--list--container">
    <h1 class="main_title">BTC Products</h1>

    {if isset($ns.error_message)}
        {include file="{ngs cmd=get_template_dir}/main/message.tpl" type="error" content="{$ns.error_message}"}
    {/if}
    {if isset($ns.success_message)}
        {include file="{ngs cmd=get_template_dir}/main/message.tpl" type="success" content="{$ns.success_message}"}
    {/if}

    {include file="{ngs cmd=get_template_dir}/main/purse/list_filters.tpl"}
    {if !empty($ns.changed_orders)}
        <h2>following orders are changed or added, {$ns.changed_orders}</h2>
    {/if}

    <div class="main-table">
        <table>
            <tr>
                <th>Actions</th>
                <th>ID</th>
                <th>Order Number</th>
                <th>Recipient</th>
                <th>Img</th>
                <th> Product Name </th>
                <th> Total </th>
                <th> % </th>
                <th> Buyer </th>
                <th> Status </th>
                <th> Note </th>
                <th> S/N </th>
                <th> amazon Order Number </th>
                <th> Tracking Number </th>
                <th> changed </th>
                <th> created </th>
            </tr>

            {foreach from=$ns.orders item=order}
                <tr {if $order->getHidden()==1}style="background: lightgray"{elseif $order->getProblematic()==1}style="background: orange"{/if}{if $order->getDeliveryDateDiffToNow()>$ns.btc_products_days_diff_for_delivery_date and $order->getHidden()==0}style="background:  #e78f08"{/if}  data-type="btc" data-id="{$order->getId()}" >
                    <td>
                        <a href="list.tpl"></a>
                        {if $order->getHidden()==0}
                            <a href="javascript:void(0);" class="fa fa-eye-slash f_hide" data-id='{$order->getId()}'></a>
                        {/if}
                        <a href="javascript:void(0);" id="problematic_{$order->getId()}" class="fa fa fa-exclamation-triangle f_problematic" data-id='{$order->getId()}'></a>
                        {if $order->getUnreadMessages() > 0}
                            <span class="fa fa-envelope" style="color: red">{$order->getUnreadMessages()}</span>
                        {/if}
                    </td>
                    <td>
                        <a href="{$SITE_PATH}/purse/{$order->getId()}">
                            <span>{$order->getId()} </span>
                        </a>
                    </td>
                    <td>
                        <a class="link" target="_black" href="https://purse.io/order/{$order->getOrderNumber()}" > {$order->getOrderNumber()} </a>
                    </td>
                    <td> {$order->getRecipientName()} ({$order->getAccountName()|replace:'purse_':''})</td>
                    <td> <img src="{$order->getImageUrl()}" width="100"/> </td>
                    <td>
                        <a class="link" target="_black" href="https://www.amazon.com/returns/cart/{$order->getAmazonOrderNumber()}" >{$order->getQuantity()} x {$order->getProductName()}</a>
                    </td>
                    <td> {$order->getAmazonTotal()} </td>
                    <td> {$order->getDiscount()} </td>
                    <td> {$order->getBuyerName()} </td>
                    <td> {$order->getStatus()} </td>
                    <td class="table-cell f_editable_cell" data-field-name="note"  > {$order->getNote()} </td>
                    <td class="table-cell f_editable_cell" data-field-name="serial_number"  > {$order->getSerialNumber()} </td>
                    <td class="table-cell f_editable_cell"  data-field-name="amazon_order_number">
                        <a class="link" target="_black" href="https://www.amazon.com/progress-tracker/package/ref=oh_aui_hz_st_btn?_encoding=UTF8&itemId=jnljnvjtqlspon&orderId={$order->getAmazonOrderNumber()}" > {$order->getAmazonOrderNumber()} </a>
                        <br/>
                        {$order->getAmazonPrimaryStatusText()}
                    </td>
                    <td>
                        <div class="f_tracking" id="tracking_{$order->getId()}">

                            {if $order->getCarrierTrackingUrl() !== false}
                                <a class="link" target="_black" href="{$order->getCarrierTrackingUrl()}" >{$order->getTrackingNumber()}</a>
                            {else}
                                {$order->getTrackingNumber()}
                            {/if}
                            <a href="javascript:void(0);" class="fa fa-refresh f_refresh_tracking" data-id='{$order->getId()}'></a>
                        </div>
                        {if $order->getDeliveryDate()>0}
                            <br/><br/>delivered at: {$order->getDeliveryDate()}
                        {/if}
                        <br/>
                        <span id="carrier_tracking_status_{$order->getId()}">{$order->getCarrierTrackingStatus()}</span>:
                        <span id="carrier_delivery_details_{$order->getId()}" style="color:#46AF04">{$order->getCarrierDeliveryDate()}</span>
                        <a href="javascript:void(0);" class="fa fa-refresh f_refresh_carrier_delivery_details" data-id='{$order->getId()}'></a>
                    </td>
                    <td> {$order->getUpdatedAt()} </td>
                    <td> {$order->getCreatedAt()} </td>
                </tr>
            {/foreach}
        </table>
    </div>
</div>