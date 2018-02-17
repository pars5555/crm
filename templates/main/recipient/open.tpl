<div class="container recipient--open--container">
    <h1 class="main_title">Partner View</h1>
    
    {if isset($ns.error_message)}
        {include file="{ngs cmd=get_template_dir}/main/message.tpl" type="error" content="{$ns.error_message}"} 
    {/if}
    {if isset($ns.success_message)}
        {include file="{ngs cmd=get_template_dir}/main/message.tpl" type="success" content="{$ns.success_message}"} 
    {/if}

    {if isset($ns.recipient)}
        <div class="table_striped table_striped_simple">
            <div class="table-row">
                <span class="table-cell">
                    id :
                </span>
                <span class="table-cell">
                    {$ns.recipient->getId()}
                </span>
            </div>
            <div class="table-row">
                <span class="table-cell">
                    name :
                </span>
                <span class="table-cell">
                    {$ns.recipient->getName()}
                </span>
            </div>
            <div class="table-row">
                <span class="table-cell">
                    email :
                </span>
                <span class="table-cell">
                    {$ns.recipient->getEmail()}
                </span>
            </div>
            <div class="table-row">
                <span class="table-cell">
                    address :
                </span>
                <span class="table-cell">
                    {$ns.recipient->getAddress()}
                </span>
            </div>
                      
            <div class="table-row">
                <span class="table-cell">
                    Recipient Orders :
                </span>
                <a class="table-cell link" href="{$SITE_PATH}/rorder/list?prt={$ns.recipient->getId()}">
                    {$ns.recipientOrders|@count}
                </a>
            </div>             
        </div>
        <form action="{$SITE_PATH}/dyn/main_recipient/do_delete_recipient">
            <input type="hidden" name="id" value="{$ns.recipient->getId()}"/>
            <a class="button blue" id="deletePartnerButton" href="javascript:void(0);">Delete</a>
        </form>
    {else}
        Wrong recipient!
    {/if}
</div>
