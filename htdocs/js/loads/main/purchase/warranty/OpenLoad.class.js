NGS.createLoad("crm.loads.main.purchase.warranty.open", {
    getContainer: function () {
        return "initialLoad";
    },
    onError: function (params) {

    },
    afterLoad: function () {
        $('#submitForm').click(function () {
            if (this.calculatePurchaseOrderLinesData()) {
                $('#purchaseOrderLinesForm').trigger('submit');
            }
        }.bind(this));

        this.initAddRemovePolSnLine();
    },
    initAddRemovePolSnLine: function () {
        $('#purchaseOrderLinesContainer').on("click", ".f_delete_polsn", function () {
            $(this).parent().parent().remove();
        });
        $('#purchaseOrderLinesContainer').on("click", ".f_add_polsn", function () {
            var snInput = $(this).parents('.snWarrantyNewLines').find('.sn');
            var warrantyInput = $(this).parents('.snWarrantyNewLines').find('.warranty');
            var serialNumber = snInput.val();
            var warrantyMonths = warrantyInput.val();
            var pol_id = $(this).attr('pol_id');
            serialNumber = serialNumber.trim();
            warrantyMonths = warrantyMonths.trim();
            if (serialNumber == '')
            {
                snInput.focus();
                return;
            }
            if (warrantyMonths == '' || !(parseInt(warrantyMonths) >= 0))
            {
                warrantyInput.focus();
                return;
            }
            snInput.val('');
            warrantyInput.val('');
            snInput.focus();

            var polSnRow = $('#purchaseOrderLineSerialNumberRowTemplate').clone();
            polSnRow.css({'display': 'table-row'});
            polSnRow.removeAttr('id');
            polSnRow.find('.sn').val(serialNumber);
            polSnRow.find('.warranty').val(warrantyMonths);
            polSnRow.appendTo("#purchaseOrderLineSerialNumbersConteiner_" + pol_id);
        });
    },
    checkNonAddedRowsThatContainsDate: function ()
    {
        var ret = true;
        $('.snWarrantyNewLines').find('.sn').each(function () {
            if ($(this).val().trim() != '')
            {
                ret = false;
                $(this).focus();
                return false;
            }
        });
        return ret;
    },
    calculatePurchaseOrderLinesData: function () {
        if (this.checkNonAddedRowsThatContainsDate() === false)
        {
            return false;
        }
        var ret = [];
        $('.purchaseOrderLineSerialNumbers').each(function () {
            var pol_id = $(this).attr('pol_id');
            var pol_serial_numbers = [];
            $(this).find(".sn").each(function () {
                pol_serial_numbers.push($(this).val());
            });
            var pol_warranty_months = [];
            $(this).find(".warranty").each(function () {
                pol_warranty_months.push($(this).val());
            });
            ret.push({'pol_id': pol_id, serial_numbers: pol_serial_numbers, warranty_months: pol_warranty_months});
        });
        $('#pols_serial_numbers').val(JSON.stringify(ret));
        return true;

    }
});
