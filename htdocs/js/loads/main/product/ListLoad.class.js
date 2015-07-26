NGS.createLoad("crm.loads.main.product.list", {
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
