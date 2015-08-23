{if $type && $content}
    <div class="message {$type}-msg" {if isset($autoClose)}data-auto-close="true"{/if}>
        <span class="fontAwesome msg-icon"></span>
        <span class="message-content">{$content}</span>
    </div>
{/if}
