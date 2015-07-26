NGS.createLoad("crm.loads.main.partner.list", {
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
