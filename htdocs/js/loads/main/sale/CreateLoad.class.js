NGS.createLoad("crm.loads.main.sale.create", {
    getContainer: function () {
        return "initialLoad";
    },
    onError: function (params) {

    },
    afterLoad: function () {
        $("select[name='partnerId']").chosen();
    }
});
