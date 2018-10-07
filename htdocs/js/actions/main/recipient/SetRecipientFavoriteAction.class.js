NGS.createAction("crm.actions.main.recipient.set_recipient_favorite", {
    onError: function (res) {
        alert(res.msg);
    },
    afterAction: function (transport) {
    }
});
