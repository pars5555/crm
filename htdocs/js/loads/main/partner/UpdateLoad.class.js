NGS.createLoad("crm.loads.main.partner.update", {
    getContainer: function () {
        return "initialLoad";
    },
    onError: function (params) {

    },
    afterLoad: function () {
        var thisInstance = this;
        $('#submitForm').click(function () {
            thisInstance.calculateInitialDeptsData();
            $('#updatePartnerForm').trigger('submit');
            return false;
        });
        this.initInitialDeptAddFunctionallity();
        this.initInitialDeptRemoveFunctionallity();
    },
    calculateInitialDeptsData: function () {
        $('.initialDeptRow').each(function () {
            var amount = $(this).find(".initialDeptAmount").val();
            var currency_id = $(this).find(".initialDeptSelectCurrency").val();
            var note = $(this).find(".initialDeptNote").val();
            var data = {amount: amount, currency_id: currency_id, note: note};
            var jsonData = JSON.stringify(data);
            $(this).find("input[type='hidden']").val(jsonData);
        });
    },
    initInitialDeptRemoveFunctionallity: function () {
        $('#initialDeptContainer').on("click", ".removeInitialDept", function () {
            $(this).closest('.initialDeptRow').remove();
        });
    },
    initInitialDeptAddFunctionallity: function () {
        $('#addInitialDeptButton').click(function () {
            var amount = $('#initialDeptAmount').val();
            var currency_id = $('#initialDeptSelectCurrency').val();
            var note = $('#initialDeptNote').val();
            if (amount <= 0)
            {
                $('#initialDeptAmount').focus();
                return;
            }
            var deptRow = $('#partnerInitialDeptTemplate').clone();
            deptRow.css({'display': 'table-row'});
            deptRow.removeAttr('id');
            deptRow.addClass('initialDeptRow');

            deptRow.find(".initialDeptAmount").val(amount);
            deptRow.find(".initialDeptSelectCurrency").val(currency_id);
            deptRow.find(".initialDeptNote").val(note);
            deptRow.appendTo("#initialDeptContainer");
            $('#initialDeptAmount').val('');
            $('#initialDeptNote').val('');
        });
    }
});
