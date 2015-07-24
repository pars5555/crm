NGS.createLoad("crm.loads.main.payments_list", {
    getContainer: function () {
        return "initialLoad";
    },
    onError: function (params) {

    },
    afterLoad: function () {
        $('#paymentFilters').find('input, select, checkbox').change(function () {
            $('#paymentFilters').trigger('submit');
        });
    }
});
