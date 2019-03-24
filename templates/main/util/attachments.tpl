{if isset($ns.attachments)}
    {assign wrap 1}
    {foreach from=$ns.attachments item=attachment}
        {if $wrap === 1 && $attachment->getEntityName() === 'checkout'}
            {assign wrap 0}
            <div style="clear: both;"></div>
        {/if}
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
<div style="clear: both;"></div>
