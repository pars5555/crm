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
                <th>Order Number</th>
                <th>Recipient</th>
                <th>Img</th>
                <th> Qty </th>
                <th> Product Name </th>
                <th> Total </th>
                <th> % </th>
                <th> Buyer </th>
                <th> Status </th>
                <th> Note </th>
                <th> S/N </th>
                <th> amazon Order Number </th>
                <th> Tracking Number </th>
                <th> hidden At </th>
                <th> created </th>
            </tr>

            {foreach from=$ns.orders item=order}
                <tr {if $order->getHidden()==1} style="background: lightgray;" {else}
                    {if $order->isDelayed()} style="background: orange;"{/if}
                    {if $order->getProblematic() == 1 && $order->getProblemSolved() == 0} style="background: yellow;"{/if}
                    {/if} data-type="btc" data-id="{$order->getId()}">
                    <td>
                        {$order->getId()}<br/>
                        
                        {if $order->getHidden()==0}
                            <a href="javascript:void(0);" class="fa fa-eye-slash fa-1x f_hide left" data-id='{$order->getId()}'></a>
                        {/if}
                        <a href="javascript:void(0);" {if $ns.problematic == 1}style="color: red"{/if} id="problematic_{$order->getId()}" class="fa fa-exclamation-triangle fa-1x f_problematic right" data-id='{$order->getId()}'></a>
                        <br/>
                        {if $order->getUnreadMessages() > 0}
                            <span class="fa fa-envelope" style="color: red">{$order->getUnreadMessages()}</span>
                        {/if}
                        <br/>
                        {if $order->getExternal() == 1}
                            <a href="javascript:void(0);" id="delete_{$order->getId()}" class="fa fa-trash fa-2x f_delete" data-id='{$order->getId()}'></a>
                        {/if}
                        {if $ns.problematic == 1 || $order->isDelayed()}
                            <a href="javascript:void(0);" id="problem_solved_{$order->getId()}" class="fa fa-check-circle fa-2x f_problem_solved" data-id='{$order->getId()}'></a>
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
                        {$order->getRecipientName()} {$order->getUnitAddress()} ({$order->getAccountName()|replace:'purse_':''})</td>

                    <td> <img src="{$order->getImageUrl()}" width="100"/> </td>
                    <td {if $order->getExternal() == 1}class="f_editable_cell"{/if} data-field-name="quantity"> {$order->getQuantity()} </td>
                    <td {if $order->getExternal() == 1}class="f_editable_cell"{/if} data-field-name="product_name">
                        <a class="link " target="_black" href="https://www.amazon.com/returns/cart/{$order->getAmazonOrderNumber()}" >{$order->getProductName()}</a>
                    </td>
                    <td {if $order->getExternal() == 1}class="f_editable_cell"{/if} data-field-name="amazon_total"> {$order->getAmazonTotal()} </td>
                    <td> {$order->getDiscount()} </td>
                    <td style="max-width: 70px;word-wrap: break-word"> {$order->getBuyerName()} </td>
                    <td> {$order->getStatus()} </td>
                    <td class="table-cell f_editable_cell" data-field-name="note" data-type="richtext" style="min-width: 100px"> {$order->getNote()} </td>
                    <td class="table-cell f_editable_cell" data-field-name="serial_number"  > {$order->getSerialNumber()} </td>
                    <td class="table-cell f_editable_cell"  data-field-name="amazon_order_number">
                        <a class="link" target="_black" href="https://www.amazon.com/progress-tracker/package/ref=oh_aui_hz_st_btn?_encoding=UTF8&itemId=jnljnvjtqlspon&orderId={$order->getAmazonOrderNumber()}" > {$order->getAmazonOrderNumber()} </a>
                        <br/>
                        {$order->getAmazonPrimaryStatusText()}
                    </td>
                    <td class="{if $order->getExternal() == 1}f_editable_cell{/if}" data-field-name="tracking_number" >
                        <div class="f_tracking" id="tracking_{$order->getId()}">

                            {if $order->getCarrierTrackingUrl() !== false}
                                <a class="link" target="_black" href="{$order->getCarrierTrackingUrl()}" >{$order->getTrackingNumber()}</a>
                            {else}
                                {$order->getTrackingNumber()}
                            {/if}
                            {if $order->getExternal() == 0}
                                <a href="javascript:void(0);" class="fa fa-refresh f_refresh_tracking" data-id='{$order->getId()}'></a>
                            {/if}
                        </div>
                        {if $order->getDeliveryDate()>0}
                            <br/><br/>delivered at: {$order->getDeliveryDate()}
                        {/if}
                        <br/>
                        <span id="carrier_tracking_status_{$order->getId()}">{$order->getCarrierTrackingStatus()}</span>:
                        <span id="carrier_delivery_details_{$order->getId()}" style="color:#46AF04">{$order->getCarrierDeliveryDate()}</span>
                        <a href="javascript:void(0);" class="fa fa-refresh f_refresh_carrier_delivery_details" data-id='{$order->getId()}'></a>
                    </td>
                    <td> {$order->getHiddenAt()} </td>
                    <td> {$order->getCreatedAt()} </td>
                </tr>
            {/foreach}
        </table>
    </div>
</div>