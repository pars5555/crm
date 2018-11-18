<div class="modal-container">
    <div class="modal-inner-container" >
        <span class="modal-close">
            <span class="close-icon1"></span>
            <span class="close-icon2"></span>
        </span>
        <h1 class="modal-headline">Receive Item</h1>
        <form class="modal-content" method="GET" action="{$SITE_PATH}/dyn/main_product/do_merge_and_delete_product">
            original name: {$ns.product->getName()}<br/><br/>
            corresponding item: 
            <select name="dst_id" id='dst_product_id' style="max-width: 700px" data-autocomplete="true" data-no-wrap="true">
                <option value="0">Select Product...</option>
                {foreach from=$ns.products item=p}
                    <option value="{$p->getId()}" {if $p->getId() == $ns.dst_product->getId()}selected{/if}>{$p->getName()}</option>
                {/foreach}

            </select> <br/>
            <input type="hidden" name='id' value="{$ns.product->getId()}"/>
            <button type="submit" class="button blue" >Merge and Delete</button>
        </form>
    </div>
</div>