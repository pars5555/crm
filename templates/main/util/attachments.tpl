{if isset($ns.attachments)}
    {foreach from=$ns.attachments item=attachment}
        <a target="_blank" href="{$SITE_PATH}/dyn/attachment/do_get_attachment?id={$attachment->getId()}">
            <div class="left">
                <img src="{$SITE_PATH}/img/attachment.png" width="50"/>
                {$attachment->getUploadedFileName()}
            </div>
        </a>
    {/foreach}
{/if}
