<div class="container manufacturer--create--container">
    <h1>Update Manufacturer</h1>
    
    {if isset($ns.error_message)}
        {include file="{getTemplateDir}/main/message.tpl" type="error" content="{$ns.error_message}"} 
    {/if}
    {if isset($ns.success_message)}
        {include file="{getTemplateDir}/main/message.tpl" type="success" content="{$ns.success_message}"} 
    {/if}

    {if isset($ns.manufacturer)}
        <form class="createManufacturer create--form" autocomplete="off" method="post" action="{SITE_PATH}/dyn/main_manufacturer/do_update_manufacturer">
            <div class="form-group">
                <label class="label">Name</label>
                <input class="text" type="text" name="name" value="{$ns.req.name|default:''}"/>
            </div>
            <div class="form-group">
                <label class="label">Link</label>
                <input class="text" type="text" name="link" value="{$ns.req.link|default:''}"/>
            </div>
            <input type="hidden" name="id" value="{$ns.manufacturer->getId()}"/>
            <input class="button blue" type="submit" value="Save"/>

        </form>
    {else}
        Wrong manufacturer!
    {/if}
</div>