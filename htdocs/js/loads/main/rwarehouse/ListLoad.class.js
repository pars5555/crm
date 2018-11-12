NGS.createLoad("crm.loads.main.rwarehouse.list", {
    search_timout_handler: null,
    getContainer: function () {
        return "initialLoad";
    },
    onError: function (params) {

    },
    afterLoad: function () {
        $('#warehouseFilters').find('select').change(function () {
            $('#warehouseFilters').trigger('submit');
        });
    }
});

