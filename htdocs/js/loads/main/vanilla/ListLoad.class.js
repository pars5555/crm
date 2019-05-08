NGS.createLoad("crm.loads.main.vanilla.list", {
    search_timout_handler: null,
    getContainer: function () {
        return "initialLoad";
    },
    onError: function (params) {

    },
    afterLoad: function () {
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
    }
});
