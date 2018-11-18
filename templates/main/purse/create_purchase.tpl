<div class="modal-container">
    <div class="modal-inner-container" >
        <span class="modal-close">
            <span class="close-icon1"></span>
            <span class="close-icon2"></span>
        </span>
        <h1 class="modal-headline">Receive Item</h1>
        <div class="modal-content">
            {foreach from=$ns.data item=product_row}
                <div class="f_purchase_container" style="border: 1px solid gray;">
                    original name: {$product_row['actual_name']}<br/>
                    corresponding item: <select class="f_purchase_item" style="max-width: 500px" data-autocomplete="true" data-no-wrap="true">
                        <option value="0">Create New Item</option>
                        <option value="-1">Don't Create Purchase</option>
                        {foreach from=$product_row['product_list'] item=p}
                            <option value="{$p->getId()}" {if $product_row['product'] && $p->getId() == $product_row['product']->getId()}selected{/if}>{$p->getName()}</option>
                        {/foreach}
                    </select><br/>
                    unit price: <input type="text" class="text width100 inline f_purchase_item_price" value="{$product_row['purchase_price']}"/>
                    unit weight: <input type="text" class="text width100 inline f_purchase_item_weight" {if $product_row['product']}value="{$product_row['product']->getUnitWeight()}"{/if}/>
                    quantity: <input type="text" class="text width100 inline f_purchase_item_quantity" value="{$product_row['quantity']}"/>

                </div>
            {/foreach}
            <a class="button blue" id="create_purchase_btn" href="javascript:void(0);">Create Purchase And hide</a>
            <input type="hidden" id="purse_order_id" value="{$ns.purse_order_id}"/>
        </div>
    </div>
</div>