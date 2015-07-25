<form class="createPartner {if !$ns.show_create_form}hide{/if}" autocomplete="off" method="post" action="{SITE_PATH}/dyn/main/do_create_partner">
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