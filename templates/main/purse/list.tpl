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
    <div class="table_striped">
        <div class="table_header_group">
            <span class="table-cell"> Actions </span>
            <span class="table-cell"> ID </span>
            <span class="table-cell"> Order Number </span>
            <span class="table-cell"> Recipient </span>
            <span class="table-cell"> Img </span>
            <span class="table-cell"> Product Name </span>
            <span class="table-cell"> Amazon Total </span>
            <span class="table-cell"> Discount </span>
            <span class="table-cell"> Buyer </span>
            <span class="table-cell"> Status </span>
            <span class="table-cell"> Note </span>
            <span class="table-cell"> S/N </span>
            <span class="table-cell"> amazon Order Number </span>
            <span class="table-cell"> Tracking Number </span>
            <span class="table-cell"> changed </span>
            <span class="table-cell"> created </span>
        </div> 
        {foreach from=$ns.orders item=order}
            <div class="table-row"  data-type="btc" data-id="{$order->getId()}" >
                <span class="table-cell"> 
                    <a href="javascript:void(0);" class="fa fa-eye-slash f_hide" data-id='{$order->getId()}'></a>
                </span>
                <a class="table-cell" href="{$SITE_PATH}/purse/{$order->getId()}">
                    <span>{$order->getId()} </span>
                </a>
                <span class="table-cell"> 
                    <a class="link" target="_black" href="https://purse.io/order/{$order->getOrderNumber()}" > {$order->getOrderNumber()} </a> 
                </span>
                <span class="table-cell"> {$order->getRecipientName()} </span>
                <span class="table-cell"> <img src="{$order->getImageUrl()}" width="100"/> </span>
                <span class="table-cell"> 
                    <a class="link" target="_black" href="https://www.amazon.com/returns/cart/{$order->getAmazonOrderNumber()}" >{$order->getProductName()}</a> 
                </span>
                <span class="table-cell"> {$order->getAmazonTotal()} </span>
                <span class="table-cell"> {$order->getDiscount()} </span>
                <span class="table-cell"> {$order->getBuyerName()} </span>
                <span class="table-cell"> {$order->getStatus()} </span>
                <span class="table-cell f_editable_cell" data-field-name="note"  > {$order->getNote()} </span>
                <span class="table-cell f_editable_cell" data-field-name="serial_number"  > {$order->getSerialNumber()} </span>
                <span class="table-cell"> 
                    <a class="link" target="_black" href="https://www.amazon.com/progress-tracker/package/ref=oh_aui_hz_st_btn?_encoding=UTF8&itemId=jnljnvjtqlspon&orderId={$order->getAmazonOrderNumber()}" > {$order->getAmazonOrderNumber()} </a> 
                </span>
                <span class="table-cell">
                    {if  strpos($order->getShippingCarrier()|lower, 'usps') !== false}
                        <a class="link" target="_black" href="https://tools.usps.com/go/TrackConfirmAction?qtc_tLabels1={$order->getTrackingNumber()}" > {$order->getTrackingNumber()}</a> 
                    {elseif  strpos($order->getShippingCarrier()|lower, 'ups') !== false}
                        <a class="link" target="_black" href="https://wwwapps.ups.com/WebTracking/track?track=yes&trackNums={$order->getTrackingNumber()}&loc=en_am"> {$order->getTrackingNumber()}</a>
                    {elseif  strpos($order->getShippingCarrier()|lower, 'fedex') !== false}
                        <a class="link" target="_black" href="https://www.fedex.com/apps/fedextrack/?action=track&trackingnumber=1Z306A400395039227{$order->getTrackingNumber()}&cntry_code=us&locale=en_US"> {$order->getTrackingNumber()}</a> 
                    {else}
                        {$order->getTrackingNumber()}
                    {/if}
                </span>
                <span class="table-cell"> {$order->getUpdatedAt()} </span>
                <span class="table-cell"> {$order->getCreatedAt()} </span>
            </div>
        {/foreach}
    </div>


</div>