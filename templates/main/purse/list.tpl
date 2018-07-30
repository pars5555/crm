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
            <span class="table-cell"> ID </span>
            <span class="table-cell"> Order Number </span>
            <span class="table-cell"> Product Name </span>
            <span class="table-cell"> Purse Total </span>
            <span class="table-cell"> Buyer </span>
            <span class="table-cell"> Status </span>
            <span class="table-cell"> amazon Order Number</span>
            <span class="table-cell"> created </span>
        </div> 
        {foreach from=$ns.orders item=order}
            <div class="table-row">
                <a class="table-cell" href="{$SITE_PATH}/purse/{$order->getId()}">
                    <span>{$order->getId()} </span>
                </a>
                <span class="table-cell"> {$order->getOrderNumber()} </span>
                <span class="table-cell"> {$order->getProductName()} </span>
                <span class="table-cell"> {$order->getPurseTotal()} </span>
                <span class="table-cell"> {$order->getBuyerName()} </span>
                <span class="table-cell" title="{$order->getStatusHistoryText()}"> {$order->getStatus()} </span>
                <span class="table-cell" title="{$order->getAmazonOrderNumberText()}" {if $order->hasMoreThanOneAmazonOrder()} style="color:red;"{/if}> {$order->getAmazonOrderNumber()} </span>
                <span class="table-cell"> {$order->getCreatedAt()} </span>
            </div>
        {/foreach}
    </div>


</div>