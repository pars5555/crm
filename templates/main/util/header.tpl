<div class="logo-container">
    <div class="menu-trigger" id="leftMenuTrigger"><span class="bar"></span></div>
    <a href="{$SITE_PATH}" class="logo">CRM.AM</a>
</div>

{if $ns.userType == $ns.userTypeAdmin}
<div class="header-links">
    <span class="languages">
        <a href="{$SITE_PATH}/language/en">en</a>
        <a href="{$SITE_PATH}/language/am">am</a>
        <a href="{$SITE_PATH}/language/ru">ru</a>
    </span>

    <a href="{$SITE_PATH}/profit/visibility">profit visibility</a>
</div>
{/if}