<div class="container product--list--container">
    <h1 class="main_title">BTC Products</h1>
    {include file="{ngs cmd=get_template_dir}/main/purse/guest_list_filters.tpl"}

    <div class="table_striped">
        <div class="table_header_group">
            <span class="table-cell"> Actions </span>
            <span class="table-cell"> ID </span>
            <span class="table-cell"> Order Number </span>
            <span class="table-cell"> Recipient </span>
            <span class="table-cell"> Img </span>
            <span class="table-cell"> Product Name </span>
            <span class="table-cell"> Total </span>
            <span class="table-cell"> % </span>
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
            <div class="table-row" {if $order->getHidden()==1}style="background: lightgray"{/if}{if $order->getDeliveryDateDiffToNow()>$ns.btc_products_days_diff_for_delivery_date and $order->getHidden()==0}style="background:  #e78f08"{/if}  data-type="btc" data-id="{$order->getId()}" >
                <span class="table-cell"> 
                    <a href="list.tpl"></a>
                    {if $order->getHidden()==0}
                        <a href="javascript:void(0);" class="fa fa-eye-slash f_hide" data-id='{$order->getId()}'></a>
                    {/if}
                    {if $order->getUnreadMessages() > 0}
                        <span class="fa fa-envelope" style="color: red">{$order->getUnreadMessages()}</span>
                    {/if}
                </span>
                <a class="table-cell" href="{$SITE_PATH}/purse/{$order->getId()}">
                    <span>{$order->getId()} </span>
                </a>
                <span class="table-cell"> 
                    <a class="link" target="_black" href="https://purse.io/order/{$order->getOrderNumber()}" > {$order->getOrderNumber()} </a> 
                </span>
                <span class="table-cell"> {$order->getRecipientName()}</span>
                <span class="table-cell"> <img src="{$order->getImageUrl()}" width="100"/> </span>
                <span class="table-cell"> 
                    <a class="link" target="_black" href="https://www.amazon.com/returns/cart/{$order->getAmazonOrderNumber()}" >{$order->getQuantity()} x {$order->getProductName()}</a> 
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
                    <span id="carrier_delivery_details_{$order->getId()}">{$order->getCarrierDeliveryDate()}</span>
                    <a href="javascript:void(0);" class="fa fa-refresh f_refresh_carrier_delivery_details" data-id='{$order->getId()}'></a>
                </span>
                <span class="table-cell"> {$order->getUpdatedAt()} </span>
                <span class="table-cell"> {$order->getCreatedAt()} </span>
            </div>
        {/foreach}
    </div>


</div>