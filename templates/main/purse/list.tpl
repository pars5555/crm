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
                    {if !empty($ns.user) && $ns.user->getType() == 'root'}
                    <th>Order Number</th>
                    {/if}
                <th>Recipient</th>
                    {if !empty($ns.user) && $ns.user->getType() == 'root'}
                    <th>Img</th>
                    <th>Product ID</th>
                    <th> Qty </th>
                    {/if}
                <th> Product Name </th>
                <th> Total </th>
                    {if !empty($ns.user) && $ns.user->getType() == 'root'}
                    <th> Caluclation Price</th>
                    {/if}
                <th> Account </th>
                    {if !empty($ns.user) && $ns.user->getType() == 'root'}
                    <th> % </th>
                    {/if}
                <th> Status </th>
                <th> Note </th>
                    {if !empty($ns.user) && $ns.user->getType() == 'root'}
                    <th> S/N </th>
                    {/if}
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
                                                        <a href="{$SITE_PATH}/btc/{$order->getId()}" class="link" target="_blank">{$order->getId()}</a><br/>

                                                        {if $order->getHidden()==0}
                                                            <a href="javascript:void(0);" class="fa fa-eye-slash fa-1x f_hide left" data-id='{$order->getId()}'></a>
                                                        {/if}

                                                        {if $order->getId()|in_array:$ns.preorder_order_ids}
                                                            <i style="color: red" href="javascript:void(0);" class="fa fa-star fa-2x left"></i>
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
                                                        {if isset($ns.attachments[$order->getId()])}
                                                            <img src="{$SITE_PATH}/img/attachment.png" width="32"/>
                                                        {/if}
                                                    </td>

                                                    {if !empty($ns.user) && $ns.user->getType() == 'root'}
                                                        <td>
                                                            <a class="link" target="_black" href="https://purse.io/order/{$order->getOrderNumber()}" > {$order->getOrderNumber()} </a>
                                                            <br/>
                                                            <span {if $order->getShippingType()=='standard'}style='color:red'{/if} >{$order->getShippingType()}</span>
                                                        </td>
                                                    {/if}
                                                    <td > 
                                                        {if not $order->getRecipientName()}
                                                            <a href="javascript:void(0);" class="fa fa-refresh f_refresh_recipient" data-id='{$order->getId()}'></a>
                                                        {/if}
                                                        {$order->getRecipientName()} 
                                                        <span class="f_editable_cell" data-field-name="unit_address">
                                                            {$order->getUnitAddress()} 
                                                        </span>
                                                    {if $order->getLocalCarrierName() === 'globbing' && isset($recipientsMappedByUnitAddress[$order->getUnitAddress()])}{$recipientsMappedByUnitAddress[$order->getUnitAddress()]->getEmail()|truncate:10:""}{/if} ({$order->getAccountName()|replace:'purse_':''})</td>

                                                {if !empty($ns.user) && $ns.user->getType() == 'root'}
                                                    <td> 
                                                        <img src="{$order->getImageUrl()}" width="100"/>
                                                        <a target="_blank" href="{$order->getCheckoutOrderProductLink()}"><img src="{$SITE_PATH}/img/link.png" width="32"/></a> </td>
                                                    <td class="f_editable_cell" data-field-name="product_id"> {$order->getProductId()|default:'N/A'} </td>
                                                    <td {if $order->getExternal() == 1}class="f_editable_cell"{/if} data-field-name="quantity"> {$order->getQuantity()} </td>
                                                {/if}
                                                <td {if $order->getExternal() == 1}class="f_editable_cell"{/if} data-field-name="product_name">
                                                    {if $order->getAmazonOrderNumber()|count_characters > 5}
                                                        <a class="link " target="_black" href="https://www.amazon.com/returns/cart?orderId={$order->getAmazonOrderNumber()}">{$order->getProductName()}</a>
                                                    {else}
                                                        {$order->getProductName()}
                                                    {/if}
                                                </td>
                                                <td {if $order->getExternal() == 1}class="f_editable_cell"{/if} data-field-name="amazon_total"> {$order->getAmazonTotal()} </td>
                                                {if !empty($ns.user) && $ns.user->getType() == 'root'}
                                                    <td class="f_editable_cell" data-field-name="supposed_purchase_price"> {$order->getSupposedPurchasePrice()} </td>
                                                {/if}
                                                <td class="f_editable_cell" data-list="account_name_list" data-field-name="account_name"> {$order->getAccountName()} </td>

                                                {if !empty($ns.user) && $ns.user->getType() == 'root'}
                                                    <td> {$order->getDiscount()} </td>
                                                {/if}
                                                <td {if $order->getExternal() == 1}class="f_selectable_cell"{/if} data-value="{$order->getStatus()}" data-field-name="status" data-template-select-id="order_status_select"> {$order->getStatus()} </td>
                                                <td class="table-cell f_editable_cell" data-field-name="note" data-type="richtext" style="min-width: 100px"> {$order->getNote()} </td>
                                                {if !empty($ns.user) && $ns.user->getType() == 'root'}
                                                    <td class="table-cell f_editable_cell" data-field-name="serial_number"  > {$order->getSerialNumber()} </td>
                                                {/if}
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

        <datalist id="account_name_list">
            {foreach from=$ns.account_names item=an}
                <option value="{$an}"/>
            {/foreach}
        </datalist>

        <select id='order_status_select' class="hidden" style="width: 120px" >
            <option value="shipping">shipping</option>
            <option value="canceled">canceled</option>
            <option value="delivered">delivered</option>
        </select>