NGS.createLoad("crm.loads.main.vanilla.list", {
    search_timout_handler: null,
    getContainer: function () {
        return "initialLoad";
    },
    onError: function (params) {

    },
    afterLoad: function () {
        $('#vanillaFilters').find('select').change(function () {
            $('#vanillaFilters').trigger('submit');
        });
        $('.f_update_card').click(function () {
            var id = $(this).data('id');
            NGS.action('crm.actions.main.vanilla.set_updated_at', {'id': id}, function(){
                window.location.reload();
            });
        });
        $('.f_closed').change(function () {
            var id = $(this).data('id');
            var checked = $(this).is(':checked') ? 1 : 0;
            NGS.action('crm.actions.main.UpdateField', {'id': id, 'object_type': 'vanilla', 'field_name': 'closed', "field_value": checked});
        });
        $('.f_balance_grow').change(function () {
            var id = $(this).data('id');
            var checked = $(this).is(':checked') ? 1 : 0;
            NGS.action('crm.actions.main.UpdateField', {'id': id, 'object_type': 'vanilla', 'field_name': 'balance_grow', "field_value": checked});
        });
    }
});
