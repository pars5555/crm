<div class="container warranty--list--container">
    <h1 class="main_title">Warranty</h1>


    <div class="table_striped">
        <div class="table_header_group">
            <span class="table-cell"> Serial Number </span>
            <span class="table-cell"> PO Warranty Months </span>
            <span class="table-cell"> SO Warranty Months </span>
            <span class="table-cell"> Purchase Order </span>
            <span class="table-cell"> Sale Order </span>
        </div> 
        {foreach from=$ns.searial_numbers item=polSolSnDtoPair key=sn}
            <div class="table-row" >
                <span class="table-cell">
                    {$sn}
                </span>
                <span class="table-cell">
                    {if isset($polSolSnDtoPair[0])}
                        {$polSolSnDtoPair[0]->getWarrantyMonths()}
                    {/if}
                </span>
                <span class="table-cell">
                    {if isset($polSolSnDtoPair[1])}
                        {$polSolSnDtoPair[1]->getWarrantyMonths()}
                    {/if}
                </span>
                <span class="table-cell">
                    {if isset($ns.purchaseOrdersDateMappedBySN[$sn])}
                        <a class="link" href="{$SITE_PATH}/sale/list?prt={$ns.purchaseOrdersDateMappedBySN[$sn][0]}">
                            {$ns.purchaseOrdersDateMappedBySN[$sn][0]}
                        </a>
                        {$ns.purchaseOrdersDateMappedBySN[$sn][1]}<br>
                        {$ns.purchaseOrdersDateMappedBySN[$sn][2]}
                    {/if}
                </span>
                <span class="table-cell">
                    {if isset($ns.saleOrdersDateMappedBySN[$sn])}
                        <a class="link" href="{$SITE_PATH}/sale/list?prt={$ns.saleOrdersDateMappedBySN[$sn][0]}">
                            {$ns.saleOrdersDateMappedBySN[$sn][0]}
                        </a>
                        {$ns.saleOrdersDateMappedBySN[$sn][1]}<br>
                        {$ns.saleOrdersDateMappedBySN[$sn][2]}
                    {/if}
                </span>
            </div> 
        {/foreach}
    </div> 



</div>