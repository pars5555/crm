<div class="logo-container">
    <div class="menu-trigger" id="leftMenuTrigger"><span class="bar"></span></div>
    <a href="{$SITE_PATH}" class="logo">CRM.AM</a>
</div>
{if !empty($ns.user) && $ns.user->getType() == 'root'}
    <div class="header-links">
        <a id="open_sticky_note" href="javascript:void(0);" style="margin-right: 100px">
            <img src="{$SITE_PATH}/img/sticky_note.png" width="45"/>
        </a>
        <span class="languages">
            <a href="{$SITE_PATH}/language/en">en</a>
            <a href="{$SITE_PATH}/language/am">am</a>
            <a href="{$SITE_PATH}/language/ru">ru</a>
        </span>

        <a href="{$SITE_PATH}/profit/visibility">profit visibility</a>

    </div>
{/if}