<div class="container partner--open--container">
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
    {if isset($ns.partner)}
        <div>
            id: {$ns.partner->getId()}
        </div>
        <div>
            name: {$ns.partner->getName()}
        </div>
        <div>
            email: {$ns.partner->getEmail()}
        </div>
        <div>
            address: {$ns.partner->getAddress()}
        </div>
       
    {/if}
</div>
