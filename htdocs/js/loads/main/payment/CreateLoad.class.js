NGS.createLoad("crm.loads.main.payment.create", {
    getContainer: function () {
        return "initialLoad";
    },
    onError: function (params) {

    },
    afterLoad: function () {
        this.initSignature();
        $("select[name='partnerId']").change(function () {
            var partner_id = $(this).val();
            NGS.action('crm.actions.main.partner.get_partner_debt', {partner_id: partner_id});
        });
        $("select[name='currencyId']").change(function () {
            this.calculationPartnerDebt();
        }.bind(this));
        $("input[name='amount']").on('input', function () {
            this.calculationPartnerDebt();
        }.bind(this));
        $('#partnerDebtHidden').on('change', function () {
            this.calculationPartnerDebt();
        }.bind(this));
    },
    initSignature: function () {
        $("#signatureContainer").jSignature();
        try {
            var signatureData = $.parseJSON($("#signatureContainer").find('span').text());
            $("#signatureContainer").jSignature('setData', signatureData, 'native');
        } catch (e) {
        }
        $(".clearSignature").click(function () {
            $("#signatureContainer").jSignature('reset');
        });
        $('.createPaymentOrder').submit(function () {
            var signatureData = $("#signatureContainer").jSignature('getData', 'native');
            $("#signature").val(JSON.stringify(signatureData));
            return true;
        });
    },
    calculationPartnerDebt: function () {
        var debt = $.parseJSON($('#partnerDebtHidden').val());
        var debtHtml = '';
        $.each(debt, function (currencyIso, amountSymbolPositionArray) {
            var amount = amountSymbolPositionArray[0];
            var symbol = amountSymbolPositionArray[1];
            var position = amountSymbolPositionArray[2];
            debtHtml += (position === 'left' ? symbol : '') + amount.toFixed(2) + (position === 'right' ? symbol : '') + '</br>';
        });
        $('#partnerDebtContainer').html(debtHtml);


        var currencySelectBox = $("select[name='currencyId']");
        var selectedCurrencyOption = $('option:selected', currencySelectBox);
        var iso = selectedCurrencyOption.attr('iso');
        var userInputAmunt = $("input[name='amount']").val();
        var debtHtml = '';
        $.each(debt, function (currencyIso, amountSymbolPositionArray) {
            var amount = amountSymbolPositionArray[0];
            var symbol = amountSymbolPositionArray[1];
            var position = amountSymbolPositionArray[2];
            if (currencyIso == iso)
            {
                amount -= !isNaN(parseFloat(userInputAmunt)) ? parseFloat(userInputAmunt) : 0;
            }
            debtHtml += (position === 'left' ? symbol : '') + amount.toFixed(2) + (position === 'right' ? symbol : '') + '</br>';
        });
        $('#partnerDebtContainer').html(debtHtml);

    }
});
