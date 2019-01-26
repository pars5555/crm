<div class="container whishlist--create--container">
    <h1 class="main_title">Create Whishlist</h1>

    {if isset($ns.error_message)}
        {include file="{ngs cmd=get_template_dir}/main/message.tpl" type="error" content="{$ns.error_message}"} 
    {/if}
    {if isset($ns.success_message)}
        {include file="{ngs cmd=get_template_dir}/main/message.tpl" type="success" content="{$ns.success_message}"} 
    {/if}

    <form class="createWhishlist create--form" autocomplete="off" method="post" action="{$SITE_PATH}/dyn/main_whishlist/do_create_whishlist">
        
       <div class="form-group">
            <label class="label">Product Name</label>
            <input type="text" class="text" name="name" value="{$ns.req.name|default:''}"/>
        </div>
       <div class="form-group">
            <label class="label">Target Price</label>
            <input type="number" class="text" name="target_price" step="0.1" value="{$ns.req.target_price|default:'0.00'}"/>
        </div>

        <div class="form-group">
            <label class="label">Amazon Asin List</label>
            <textarea class="text" name="asin_list">{$ns.req.asin_list|default:''}</textarea>
        </div>
        <input class="button blue" type="submit" value="Save"/>
    </form>
</div>
