NGS.createLoad("crm.loads.main.partners_list", {
    getContainer: function () {
        return "initialLoad";
    },
    onError: function (params) {

    },
    afterLoad: function () {
        $('#partnerFilters').find('input, select, checkbox').change(function () {
            $('#partnerFilters').trigger('submit');
        });
    }
});
