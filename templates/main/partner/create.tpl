<div>
    {include file="{getTemplateDir}/main/left_menu.tpl"}
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
    <form class="createPartner" autocomplete="off" method="post" action="{SITE_PATH}/dyn/main/do_create_partner">
        <div>
            <label>Name</label>
            <input type="text" name="name" value="{$ns.req.name|default:''}"/>
        </div>
        <div>
            <label>Email</label>
            <input type="email" name="email" value="{$ns.req.email|default:''}"/>
        </div>
        <div>
            <label>Address</label>
            <input type="text" name="address" value="{$ns.req.address|default:''}"/>
        </div>
        <input type="submit" value="Save"/>

    </form>
</div>