NGS.createAction("crm.actions.main.purse.add_external_order", {
    onError: function (res) {
    },
    afterAction: function (res) {
        window.location.reload();
    }
});
