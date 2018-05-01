NGS.createLoad("crm.loads.main.recipient.update", {
    getContainer: function () {
        return "initialLoad";
    },
    onError: function (params) {

    },
    afterLoad: function () {
        var thisInstance = this;
        $('#submitForm').click(function () {
            thisInstance.calculateInitialDebtsData();
            $('#updateRecipientForm').trigger('submit');
            return false;
        });
        this.initInitialDebtAddFunctionallity();
        this.initInitialDebtRemoveFunctionallity();
    },
    calculateInitialDebtsData: function () {
        $('.initialDebtRow').each(function () {
            var amount = $(this).find(".initialDebtAmount").val();
            var currency_id = $(this).find(".initialDebtSelectCurrency").val();
            var note = $(this).find(".initialDebtNote").val();
            var data = {amount: amount, currency_id: currency_id, note: note};
            var jsonData = JSON.stringify(data);
            $(this).find("input[type='hidden']").val(jsonData);
        });
    },
    initInitialDebtRemoveFunctionallity: function () {
        $('#initialDebtContainer').on("click", ".removeInitialDebt", function () {
            $(this).closest('.initialDebtRow').remove();
        });
    },
    initInitialDebtAddFunctionallity: function () {
        $('#addInitialDebtButton').click(function () {
            var amount = $('#initialDebtAmount').val();
            var currency_id = $('#initialDebtSelectCurrency').val();
            var note = $('#initialDebtNote').val();
            if (amount <= 0)
            {
                $('#initialDebtAmount').focus();
                return;
            }
            var debtRow = $('#recipientInitialDebtTemplate').clone();
            debtRow.css({'display': 'table-row'});
            debtRow.removeAttr('id');
            debtRow.addClass('initialDebtRow');

            debtRow.find(".initialDebtAmount").val(amount);
            debtRow.find(".initialDebtSelectCurrency").val(currency_id);
            debtRow.find(".initialDebtNote").val(note);
            debtRow.appendTo("#initialDebtContainer");
            $('#initialDebtAmount').val('');
            $('#initialDebtNote').val('');
        });
    }
});
