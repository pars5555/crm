NGS.createLoad("crm.loads.main.whishlist.list", {
    getContainer: function () {
        return "initialLoad";
    },
    onError: function (params) {

    },
    afterLoad: function () {
       $('#whishlistFilters').find('select, checkbox').change(function () {
            $('#whishlistFilters').trigger('submit');
        });
    }
});
