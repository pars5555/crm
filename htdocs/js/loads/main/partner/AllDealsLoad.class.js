NGS.createLoad("crm.loads.main.partner.all_deals", {
    getContainer: function () {
        return "initialLoad";
    },
    onError: function (params) {

    },
    afterLoad: function () {
        this.initChecked();
    },
    initChecked: function () {
        $('.f_checked_checkbox').change(function () {
            var id = $(this).data('id');
            var checked = $(this).is(':checked') ? 1 : 0;
            var object_type = $(this).data('type');
            NGS.action('crm.actions.main.set_object_checked', {object_type: object_type, id: id, checked: checked});
        });
    }
});
