<div class="container whishlist--open--container">
    <h1 class="main_title">Whishlist View</h1>

    {if isset($ns.error_message)}
        {include file="{ngs cmd=get_template_dir}/main/message.tpl" type="error" content="{$ns.error_message}"}
    {/if}
    {if isset($ns.success_message)}
        {include file="{ngs cmd=get_template_dir}/main/message.tpl" type="success" content="{$ns.success_message}"}
    {/if}

    {if isset($ns.whishlist)}
        <div class="table_striped table_striped_simple">
            <div class="table-row">
                <span class="table-cell">
                    id :
                </span>
                <span class="table-cell">
                    {$ns.whishlist->getId()}
                </span>
            </div>
            <div class="table-row">
                <span class="table-cell">
                    Name:
                </span>
                <span class="table-cell">
                    {$ns.whishlist->getName()}
                </span>
            </div>
            <div class="table-row">
                <span class="table-cell">
                    Target Price:
                </span>
                <span class="table-cell">
                    {$ns.whishlist->getTargetPrice()}
                </span>
            </div>

            <div class="table-row">
                <span class="table-cell">
                    Asin List :
                </span>
                <span class="table-cell">
                    {$ns.whishlist->getAsinList()}
                </span>
            </div>
        </div>


    {/if}
</div>
