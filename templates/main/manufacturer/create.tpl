<div class="container manufacturer--create--container">
    <h1>Create Manufacturer</h1>
    {if isset($ns.error_message)}
        <div>
            <span style="color:red">{$ns.error_message}</span>
        </div>
    {/if}
    {if isset($ns.success_message)}
        <div>
            <span style="color:green">{$ns.success_message}</span>
        </div>
    {/if}

    <form class="createManufacturer create--form" autocomplete="off" method="post" action="{SITE_PATH}/dyn/main_manufacturer/do_create_manufacturer">
        <div class="form-group">
            <label class="label">Name</label>
            <input class="text" type="text" name="name" value="{$ns.req.name|default:''}"/>
        </div>
        <div class="form-group">
            <label class="label">Link</label>
            <input class="text" type="text" name="link" value="{$ns.req.link|default:''}"/>
        </div>
        <input class="button blue" type="submit" value="Save"/>

    </form>
</div>