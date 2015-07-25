<div>
    {include file="{getTemplateDir}/main/partner/partners_list_filters.tpl"}
    <div>
        <span> ID </span>
        <span> Name</span>
        <span> Email </span>
        <span> Address </span>
    </div> 
    {foreach from=$ns.partners item=partner}
        <div>
            <a href="{SITE_PATH}/partner/{$partner->getId()}">{$partner->getId()} </a>
            <span> {$partner->getName()} </span>
            <span> {$partner->getEmail()} </span>
            <span> {$partner->getAddress()} </span>
        </div>
    {/foreach}

</div>