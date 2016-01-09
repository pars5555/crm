NGS.createLoad("crm.loads.main.general.cashbox", {
    getContainer: function () {
        return "cashboxCalculationContainer";
    },
    onError: function (params) {

    },
    afterLoad: function () {
        $('.cashboxContainer select').not("[data-no-wrap=true]").wrap("<div class='select_wrapper' />");
        $('#cashboxCurrencySelect').change(function () {
            var params = {cur: $('#cashboxCurrencySelect').val(), date: $('#cashboxDate').val()};
            NGS.load('crm.loads.main.general.cashbox', params);
        });
        $('#cashboxDate').datetimepicker({
            format: 'Y-m-d',
            inline: true,
            timepicker: false,
            step: 1,
            lang:'hy',
            onSelectDate: function (ct, $i) {
                var params = {cur: $('#cashboxCurrencySelect').val(), date: ct.dateFormat('Y-m-d')};
                NGS.load('crm.loads.main.general.cashbox', params);
            }
        });
    }
});
