<div class="container partner--create--container">
    <h1>Create Partner</h1>
    
    {if isset($ns.error_message)}
        {include file="$TEMPLATE_DIR/main/message.tpl" type="error" content="{$ns.error_message}"} 
    {/if}
    {if isset($ns.success_message)}
        {include file="$TEMPLATE_DIR/main/message.tpl" type="success" content="{$ns.success_message}"} 
    {/if}


    <form class="createPartner create--form" autocomplete="off" method="post" action="{SITE_PATH}/dyn/main_partner/do_create_partner">
        <div class="form-group">
            <label class="label">Name</label>
            <input class="text" type="text" name="name" value="{$ns.req.name|default:''}"/>
        </div>
        <div class="form-group">
            <label class="label">Email</label>
            <input class="text" type="email" name="email" value="{$ns.req.email|default:''}"/>
        </div>
        <div class="form-group">
            <label class="label">Address</label>
            <input class="text" type="text" name="address" value="{$ns.req.address|default:''}"/>
        </div>
        <div class="form-group">
            <label class="label">Phone</label>
            <input class="text" type="text" name="phone" value="{$ns.req.phone|default:''}"/>
        </div>

        <input class="button blue" type="submit" value="Save"/>

    </form>
</div>