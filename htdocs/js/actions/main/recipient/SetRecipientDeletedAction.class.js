NGS.createAction("crm.actions.main.recipient.set_recipient_deleted", {
    onError: function (res) {
        alert(res.msg);
    },
    afterAction: function (transport) {
    }
});
