NGS.createLoad("crm.loads.main.general.cashbox", {
    getContainer: function () {
        return "cashboxCalculationContainer";
    },
    onError: function (params) {

    },
    afterLoad: function () {
        $('.cashboxContainer select').not("[data-no-wrap=true]").wrap("<div class='select_wrapper' />");
        $('.cashboxContainer select').change(function () {
            var year = $("select[name='dateYear']").val();
            var month = $("select[name='dateMonth']").val();
            var day = $("select[name='dateDay']").val();
            var params = {cur: $('#cashboxCurrencySelect').val(), dateYear: year, dateMonth: month, dateDay: day};
            NGS.load('crm.loads.main.general.cashbox', params);
        });
    }

});
