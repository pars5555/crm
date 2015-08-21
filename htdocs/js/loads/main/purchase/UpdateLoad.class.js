NGS.createLoad("crm.loads.main.purchase.update", {
    getContainer: function () {
        return "initialLoad";
    },
    onError: function (params) {

    },
    afterLoad: function () {
      $("select[name='partnerId']").chosen();
    }
});
