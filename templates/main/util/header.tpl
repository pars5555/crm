<div class="logo-container">
    <div class="menu-trigger" id="leftMenuTrigger"><span class="bar"></span></div>
    <a href="{$SITE_PATH}" class="logo">CRM.AM</a>
</div>
<div class="header-links">
    {if !empty($ns.user) && $ns.user->getType() == 'root'}
        <a id="open_sticky_note" href="javascript:void(0);" style="margin-right: 100px">
            <img src="{$SITE_PATH}/img/sticky_note.png" width="45"/>
        </a>
    {/if}
    <span class="languages">
        <a href="{$SITE_PATH}/language/en" {if ($ns.currentLang|default:'en')=='en'}class="active" style="font-weight:bold;text-decoration:underline;"{/if}>en</a>
        <a href="{$SITE_PATH}/language/am" {if ($ns.currentLang|default:'en')=='am'}class="active" style="font-weight:bold;text-decoration:underline;"{/if}>am</a>
        <a href="{$SITE_PATH}/language/ru" {if ($ns.currentLang|default:'en')=='ru'}class="active" style="font-weight:bold;text-decoration:underline;"{/if}>ru</a>
    </span>
    {if !empty($ns.user) && $ns.user->getType() == 'root'}
        <a href="{$SITE_PATH}/profit/visibility">{$ns.lm->getPhrase('profit visibility')|default:'profit visibility'}</a>
    {/if}
</div>