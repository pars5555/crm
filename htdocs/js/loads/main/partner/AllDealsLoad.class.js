NGS.createLoad("crm.loads.main.partner.all_deals", {
    getContainer: function () {
        return "initialLoad";
    },
    onError: function (params) {

    },
    afterLoad: function () {
        this.initChecked();
        this.initCheckAllCheckbox();
    },
    initChecked: function () {
        $('.f_checked_checkbox').change(function () {
            var id = $(this).data('id');
            var checked = $(this).is(':checked') ? 1 : 0;
            var object_type = $(this).data('type');
            NGS.action('crm.actions.main.set_object_checked', {object_type: object_type, id: id, checked: checked});
        });
    },
    initCheckAllCheckbox: function () {
        $('#f_check_all_checkbox').change(function () {
            var destClass = $(this).data('destination_class');
            var count = $('.' + destClass).length;
            if (count > 50) {
                if (window.confirm('It will change ' + count + ' checkbox selection, are you sure?'))
                {
                    $('.' + destClass).prop("checked", $(this).is(":checked"));
                    $('.' + destClass).trigger('change');
                }
            } else
            {
                $('.' + destClass).prop("checked", $(this).is(":checked"));
                $('.' + destClass).trigger('change');
            }
        });
    }
});
