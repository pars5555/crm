<div class="container manufacturer--list--container">
    <h1>Manufacturers</h1>
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

    <a href="{SITE_PATH}/manufacturer/create"><img src="{SITE_PATH}/img/add.png"/></a>
    <div class="table_striped">
        <div class="table_header_group">
            <span class="table-cell"> ID </span>
            <span class="table-cell"> Name</span>
            <span class="table-cell"> Link </span>
            <span class="table-cell"> View </span>
        </div> 
        {foreach from=$ns.manufacturers item=manufacturer}
            <div class="table-row">
                <span class="table-cell">{$manufacturer->getId()} </span>
                <span class="table-cell">{$manufacturer->getName()} </span>
                <span class="table-cell"> {$manufacturer->getLink()} </span>
                <a class="table-cell view_item" href="{SITE_PATH}/manufacturer/edit/{$manufacturer->getId()}">
                    <span class="button blue">edit</span>
                </a>
            </div>
        {/foreach}
    </div>


</div>