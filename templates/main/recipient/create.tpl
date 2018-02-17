<div class="container recipient--create--container">
    <h1 class="main_title">Create Recipient</h1>
    
    {if isset($ns.error_message)}
        {include file="{ngs cmd=get_template_dir}/main/message.tpl" type="error" content="{$ns.error_message}"} 
    {/if}
    {if isset($ns.success_message)}
        {include file="{ngs cmd=get_template_dir}/main/message.tpl" type="success" content="{$ns.success_message}"} 
    {/if}


    <form class="createRecipient create--form" autocomplete="off" method="post" action="{$SITE_PATH}/dyn/main_recipient/do_create_recipient">
        <div class="form-group">
            <label class="label">Name</label>
            <input class="text" type="text" name="name" value="{$ns.req.name|default:''}"/>
        </div>
        <div class="form-group">
            <label class="label">Email</label>
            <input class="text" type="email" name="email" value="{$ns.req.email|default:''}"/>
        </div>
        <div class="form-group">
            <label class="label">Documents</label>
            <input class="text" type="text" name="documents" value="{$ns.req.documents|default:''}"/>
        </div>
        <div class="form-group">
            <label class="label">Meta Data</label>
            <input class="text" type="text" name="meta" value="{$ns.req.meta|default:''}"/>
        </div>
        <div class="form-group">
            <label class="label">Phone</label>
            <input class="text" type="text" name="phone" value="{$ns.req.phone|default:''}"/>
        </div>

        <input class="button blue" type="submit" value="Save"/>

    </form>
</div>