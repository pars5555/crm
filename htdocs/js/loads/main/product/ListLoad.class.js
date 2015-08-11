NGS.createLoad("crm.loads.main.product.list", {
    getContainer: function () {
        return "initialLoad";
    },
    onError: function (params) {

    },
    afterLoad: function () {
        $('#productFilters').find('input, select, checkbox').change(function () {
            $('#productFilters').trigger('submit');
        });
    }
});
