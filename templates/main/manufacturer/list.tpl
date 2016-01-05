<div class="container manufacturer--list--container">
    <h1 class="main_title">Manufacturers</h1>
    
    {if isset($ns.error_message)}
        {include file="{ngs cmd=get_template_dir}/main/message.tpl" type="error" content="{$ns.error_message}"} 
    {/if}
    {if isset($ns.success_message)}
        {include file="{ngs cmd=get_template_dir}/main/message.tpl" type="success" content="{$ns.success_message}"} 
    {/if}

    <a href="{$SITE_PATH}/manufacturer/create"><img src="{$SITE_PATH}/img/add.png"/></a>
    <div class="table_striped">
        <div class="table_header_group">
            <span class="table-cell"> ID </span>
            <span class="table-cell"> Name</span>
            <span class="table-cell"> Link </span>
            <span class="table-cell"> Edit </span>
        </div> 
        {foreach from=$ns.manufacturers item=manufacturer}
            <div class="table-row">
                <span class="table-cell">{$manufacturer->getId()} </span>
                <span class="table-cell">{$manufacturer->getName()} </span>
                <span class="table-cell"> {$manufacturer->getLink()} </span>
                <a class="table-cell" href="{$SITE_PATH}/manufacturer/edit/{$manufacturer->getId()}">
                    <span class="button_icon" title="Edit">
                        <i class="fa fa-pencil"></i>
                    </span>
                </a>
            </div>
        {/foreach}
    </div>


</div>