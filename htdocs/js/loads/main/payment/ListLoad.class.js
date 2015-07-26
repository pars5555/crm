NGS.createLoad("crm.loads.main.payment.list", {
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
