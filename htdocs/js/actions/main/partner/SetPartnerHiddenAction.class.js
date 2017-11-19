NGS.createAction("crm.actions.main.partner.set_partner_hidden", {
    onError: function (res) {
        alert(res.msg);
    },
    afterAction: function (transport) {
    }
});
