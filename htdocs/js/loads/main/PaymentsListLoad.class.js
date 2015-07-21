NGS.createLoad("crm.loads.main.payments_list", {
    getContainer: function () {
        return "initialLoad";
    },
    onError: function (params) {

    },
    afterLoad: function () {
    }
});
