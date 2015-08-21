NGS.createAction("crm.actions.main.partner.get_partner_dept", {
    onError: function (res) {
        alert(res.msg);
    },
    afterAction: function (transport) {
        var deptHtml = '';
        $.each(transport.params, function (currencyIso, amountSymbolPositionArray) {
            var amount = amountSymbolPositionArray[0];
            var symbol = amountSymbolPositionArray[1];
            var position = amountSymbolPositionArray[2];
            deptHtml += (position === 'left' ? symbol : '') + amount.toFixed(2) + (position === 'right' ? symbol : '') +'</br>';
        });
        $('#partnerDeptContainer').html(deptHtml);
    }
});
