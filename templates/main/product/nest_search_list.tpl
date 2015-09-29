<div>
    {foreach from=$ns.products item=product}
        <div> {$product->getName()} <br></div>
        {/foreach}
</div>