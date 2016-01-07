NGS.createAction("crm.actions.main.partner.get_partner_dept", {
    onError: function (res) {
        alert(res.msg);
    },
    afterAction: function (transport) {
        $('#partnerDeptHidden').val(JSON.stringify(transport)).trigger('change');
    }
});
