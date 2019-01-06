{if isset($ns.attachments)}
    {foreach from=$ns.attachments item=attachment}
        <a target="_blank" href="{$SITE_PATH}/dyn/attachment/do_get_attachment?id={$attachment->getId()}">
            <div class="left " style="margin:10px;border:1px solid gray">
                <img src="{$SITE_PATH}/img/attachment.png" width="50"/>
                {$attachment->getUploadedFileName()}
                <a class="button_icon f_remove_attachment link" data-id="{$attachment->getId()}" title="delete attachment">
                    <i class="fa fa-trash-o"></i>
                </a>
                <div>
                    {$attachment->getEntityName()}: {$attachment->getEntityId()}
                </div>
            </div>
        </a>
    {/foreach}
{/if}
