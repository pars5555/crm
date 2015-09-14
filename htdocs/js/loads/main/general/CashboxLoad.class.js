NGS.createLoad("crm.loads.main.general.cashbox", {
    getContainer: function () {
        return "cashboxCalculationContainer";
    },
    onError: function (params) {

    },
    afterLoad: function () {
        $('#cashboxDate').datetimepicker({
            format: 'Y-m-d',
            inline: true,
            timepicker: false,
            step: 1,
            onSelectDate: function (ct, $i) {
                var params = {cur: $('#cashboxCurrencySelect').val(), date: ct.dateFormat('Y-m-d')};
                NGS.load('crm.loads.main.general.cashbox', params);
            }
        });
    }
});
