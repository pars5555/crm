NGS.createLoad("crm.loads.main.vanilla.list", {
    search_timout_handler: null,
    getContainer: function () {
        return "initialLoad";
    },
    onError: function (params) {

    },
    afterLoad: function () {
        $('.f_lockable').addClass('f_locked');
        $('#lock_checkbox').change(function () {
            if ($(this).is(":checked"))
            {
                $('.f_lockable').addClass('f_locked');
            }else{
                $('.f_lockable').removeClass('f_locked');
                
            }
        });
        $('#vanillaFilters').find('select').change(function () {
            $('#vanillaFilters').trigger('submit');
        });
        $('.f_update_card').click(function () {
            var id = $(this).data('id');
            NGS.action('crm.actions.main.vanilla.updated_asap', {'id': id}, function(){
                window.location.reload();
            });
        });
        $('.f_closed').change(function () {
            var id = $(this).data('id');
            var checked = $(this).is(':checked') ? 1 : 0;
            NGS.action('crm.actions.main.UpdateField', {'id': id, 'object_type': 'vanilla', 'field_name': 'closed', "field_value": checked});
        });
        $('.f_attention').change(function () {
            var id = $(this).data('id');
            var checked = $(this).is(':checked') ? 1 : 0;
            NGS.action('crm.actions.main.UpdateField', {'id': id, 'object_type': 'vanilla', 'field_name': 'attention', "field_value": checked});
        });
        $('.f_balance_grow').change(function () {
            var id = $(this).data('id');
            var checked = $(this).is(':checked') ? 1 : 0;
            NGS.action('crm.actions.main.UpdateField', {'id': id, 'object_type': 'vanilla', 'field_name': 'balance_grow', "field_value": checked});
        });
    }
});
