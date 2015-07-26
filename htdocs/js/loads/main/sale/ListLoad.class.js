NGS.createLoad("crm.loads.main.sale.list", {
    getContainer: function () {
        return "initialLoad";
    },
    onError: function (params) {

    },
    afterLoad: function () {
        $('#saleOrderFilters').find('input, select, checkbox').change(function () {
            $('#saleOrderFilters').trigger('submit');
        });
    }
});
