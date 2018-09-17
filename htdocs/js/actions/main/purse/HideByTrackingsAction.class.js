NGS.createAction("crm.actions.main.purse.hide_by_trackings", {
    onError: function (res) {
    },
    afterAction: function (res) {
        window.location.reload();
    }
});
