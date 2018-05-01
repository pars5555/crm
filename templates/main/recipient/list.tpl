<div class="container partner--list--container">
    <h1 class="main_title">Recipients</h1>

    {if isset($ns.error_message)}
        {include file="{ngs cmd=get_template_dir}/main/message.tpl" type="error" content="{$ns.error_message}"} 
    {/if}
    {if isset($ns.success_message)}
        {include file="{ngs cmd=get_template_dir}/main/message.tpl" type="success" content="{$ns.success_message}"} 
    {/if}

    {include file="{ngs cmd=get_template_dir}/main/recipient/list_filters.tpl"}
    <a href="{$SITE_PATH}/recipient/create"><img src="{$SITE_PATH}/img/add.png"/></a>
    <div class="table_striped">
        <div class="table_header_group">
            <span class="table-cell"> ID </span>
            <span class="table-cell"> Name</span>
            <span class="table-cell"> Email </span>
            <span class="table-cell"> Tel. </span>
            <span class="table-cell"> Favorite </span>
            <span class="table-cell"> Orders</span>           
            <span class="table-cell"> View </span>
            <span class="table-cell"> Edit </span>
            <span class="table-cell"> Hidden </span>
        </div> 
        {foreach from=$ns.recipients item=recipient}
            <div class="table-row">
                <a class="table-cell" href="{$SITE_PATH}/recipient/{$recipient->getId()}">
                    <span>{$recipient->getId()} </span>
                </a>
                <span class="table-cell">{$recipient->getName()} </span>
                <span class="table-cell"> {$recipient->getEmail()} </span>
                <span class="table-cell " style="white-space: nowrap"> {$recipient->getPhone()|replace:',':'</br>'} </span>
                
                <span class="table-cell "> <input class="f_favorite_checkbox" data-recipient_id="{$recipient->getId()}" 
                                                  type="checkbox" value="1" {if $recipient->getFavorite() ==1}checked{/if}/></span>                
                
                <a class="table-cell link" href="{$SITE_PATH}/rorder/list?prt={$recipient->getId()}"> {$ns.recipientsOrdersMappedByRecipientId[$recipient->getId()]|@count} </a>
              
                <a class="table-cell view_item" href="{$SITE_PATH}/recipient/{$recipient->getId()}">
                    <span class="button_icon" title="View">
                        <i class="fa fa-eye"></i>
                    </span>
                </a>
                <a class="table-cell view_item" href="{$SITE_PATH}/recipient/edit/{$recipient->getId()}">
                    <span class="button_icon" title="Edit">
                        <i class="fa fa-pencil"></i>
                    </span>
                </a>
                <span class="table-cell "> <input class="f_hidden_checkbox" data-recipient_id="{$recipient->getId()}" type="checkbox" value="1" {if $recipient->getHidden() ==1}checked{/if}/></span>
            </div>
        {/foreach}
    </div>


</div>