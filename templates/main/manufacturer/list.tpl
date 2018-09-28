<div class="container manufacturer--list--container">
    <h1 class="main_title">Manufacturers</h1>
    
    {if isset($ns.error_message)}
        {include file="{ngs cmd=get_template_dir}/main/message.tpl" type="error" content="{$ns.error_message}"} 
    {/if}
    {if isset($ns.success_message)}
        {include file="{ngs cmd=get_template_dir}/main/message.tpl" type="success" content="{$ns.success_message}"} 
    {/if}

    <a href="{$SITE_PATH}/manufacturer/create"><img src="{$SITE_PATH}/img/add.png"/></a>

    <div class="main-table">
        <table>
            <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Link</th>
                <th class="icon-cell">Edit</th>
            </tr>
            {foreach from=$ns.manufacturers item=manufacturer}
                <tr>
                    <td>{$manufacturer->getId()}</td>
                    <td>{$manufacturer->getName()}</td>
                    <td>{$manufacturer->getLink()}</td>
                    <td class="icon-cell">
                        <a href="{$SITE_PATH}/manufacturer/edit/{$manufacturer->getId()}">
                            <span class="button_icon" title="Edit">
                                <i class="fa fa-pencil"></i>
                            </span>
                        </a>
                    </td>
                </tr>
            {/foreach}
        </table>
    </div>
</div>