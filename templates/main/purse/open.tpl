<div class="container product--open--container">
    <h1 class="main_title">BTC Order View</h1>
    <div class="table_striped table_striped_simple">
        <div class="table-row">
            <span class="table-cell">
                id :
            </span>
            <span class="table-cell">
                {$ns.order->getId()}
            </span>
        </div>
        <div class="table-row">
            <span class="table-cell">
                Name :
            </span>
            <span class="table-cell">
                {$ns.order->getOrderNumber()|default:'external'}
            </span>
        </div>
        <div class="table-row">
            <span class="table-cell">
                Note :
            </span>
            <span class="table-cell">
                {$ns.order->getNote()}
            </span>
        </div>
    </div>
</div>

{include file="{ngs cmd=get_template_dir}/main/util/attachments.tpl"} 
            
<form id="upload_attachment_form" target="upload_target" enctype="multipart/form-data" method="post" action="{$SITE_PATH}/dyn/attachment/do_upload" autocomplete="off">
    <a class="button blue" id="select_attachment_button" >select attachment...</a>
    <input type="hidden" name="entity_id" value="{$ns.order->getId()}"/>
    <input type="hidden" name="entity_name" value="btc"/>
    <input type="hidden" name="partner_id" value="0"/>
    <input id="file_input" name="file" type="file" style="display:none" />
</form>
<iframe id="upload_target" name="upload_target" style="width:0;height:0;border:0px solid #fff;display: none;" ></iframe>
