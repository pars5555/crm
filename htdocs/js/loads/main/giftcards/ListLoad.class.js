NGS.createLoad("crm.loads.main.giftcards.list", {
    search_timout_handler: null,
    getContainer: function () {
        return "initialLoad";
    },
    onError: function (params) {

    },
    afterLoad: function () {
        $('#giftcardsFilters').find('select').change(function () {
            $('#giftcardsFilters').trigger('submit');
        });
        $('.f_attention').change(function () {
            var id = $(this).data('id');
            var checked = $(this).is(':checked') ? 1 : 0;
            NGS.action('crm.actions.main.UpdateField', {'id': id, 'object_type': 'giftcards', 'field_name': 'attention', "field_value": checked});
        });
    }
});
