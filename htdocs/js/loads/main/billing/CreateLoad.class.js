NGS.createLoad("crm.loads.main.billing.create", {
    getContainer: function () {
        return "initialLoad";
    },
    onError: function (params) {

    },
    afterLoad: function () {
        $("select[name='partnerId']").chosen();

        $("select[name='partnerId']").change(function () {
            var partner_id = $(this).val();
            NGS.action('crm.actions.main.partner.get_partner_dept', {partner_id: partner_id});
        });
        $("select[name='currencyId']").change(function () {
            this.calculationPartnerDept();
        }.bind(this));
        $("input[name='amount']").on('input', function () {
            this.calculationPartnerDept();
        }.bind(this));
        $('#partnerDeptHidden').on('change', function () {
            this.calculationPartnerDept();
        }.bind(this));
        
        
    },
    calculationPartnerDept: function () {
        var dept = $.parseJSON($('#partnerDeptHidden').val());
        var deptHtml = '';
        $.each(dept, function (currencyIso, amountSymbolPositionArray) {
            var amount = amountSymbolPositionArray[0];
            var symbol = amountSymbolPositionArray[1];
            var position = amountSymbolPositionArray[2];
            deptHtml += (position === 'left' ? symbol : '') + amount.toFixed(2) + (position === 'right' ? symbol : '') + '</br>';
        });
        $('#partnerDeptContainer').html(deptHtml);


        var currencySelectBox = $("select[name='currencyId']");
        var selectedCurrencyOption = $('option:selected', currencySelectBox);
        var iso = selectedCurrencyOption.attr('iso');
        var userInputAmunt = $("input[name='amount']").val();
        var deptHtml = '';
        $.each(dept, function (currencyIso, amountSymbolPositionArray) {
            var amount = amountSymbolPositionArray[0];
            var symbol = amountSymbolPositionArray[1];
            var position = amountSymbolPositionArray[2];
            if (currencyIso == iso)
            {
                amount -= !isNaN(parseFloat(userInputAmunt))?parseFloat(userInputAmunt):0;
            }
            deptHtml += (position === 'left' ? symbol : '') + amount.toFixed(2) + (position === 'right' ? symbol : '') + '</br>';
        });
        $('#partnerDeptContainer').html(deptHtml);
        
    }
});
