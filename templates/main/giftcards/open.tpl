<div class="container product--open--container">
    <h1 class="main_title">GC View</h1>
    <div class="table_striped table_striped_simple">
        <div class="table-row">
            <span class="table-cell">
                id :
            </span>
            <span class="table-cell">
                {$ns.gc->getId()}
            </span>
        </div>
        <div class="table-row">
            <span class="table-cell">
                Note :
            </span>
            <span class="table-cell">
                {$ns.gc->getNote()}
            </span>
        </div>
    </div>
</div>

{include file="{ngs cmd=get_template_dir}/main/util/attachments.tpl"} 

<form id="upload_attachment_form" target="upload_target" enctype="multipart/form-data" method="post" action="{$SITE_PATH}/dyn/attachment/do_upload" autocomplete="off">
    <a class="button blue" id="select_attachment_button" >select attachment...</a>
    <input type="hidden" name="entity_id" value="{$ns.gc->getId()}"/>
    <input type="hidden" name="entity_name" value="giftcard"/>
    <input type="hidden" name="partner_id" value="{$ns.gc->getPartnerId()}"/>
    <input id="file_input" name="file" type="file" style="display:none" />
</form>
<iframe id="upload_target" name="upload_target" style="width:0;height:0;border:0px solid #fff;display: none;" ></iframe>
