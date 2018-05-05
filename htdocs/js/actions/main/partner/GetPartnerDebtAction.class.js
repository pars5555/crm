NGS.createAction("crm.actions.main.partner.get_partner_debt", {
    onError: function (res) {
        alert(res.msg);
    },
    afterAction: function (transport) {
        $('#partnerDebtHidden').val(JSON.stringify(transport)).trigger('change');
    }
});
