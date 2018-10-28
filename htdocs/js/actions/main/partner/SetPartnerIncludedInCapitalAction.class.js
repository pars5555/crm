NGS.createAction("crm.actions.main.partner.set_partner_included_in_capital", {
    onError: function (res) {
        alert(res.msg);
    },
    afterAction: function (transport) {
    }
});
