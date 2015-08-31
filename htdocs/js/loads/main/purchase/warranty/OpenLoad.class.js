NGS.createLoad("crm.loads.main.purchase.warranty.open", {
    getContainer: function () {
        return "initialLoad";
    },
    onError: function (params) {

    },
    afterLoad: function () {
        $('#submitForm').click(function () {
            this.calculatePurchaseOrderLinesData();
            $('#purchaseOrderLinesForm').trigger('submit');
        }.bind(this));

        this.initAddRemovePolSnLine();
    },
    initAddRemovePolSnLine: function () {
        $('#purchaseOrderLinesContainer').on("click", ".f_delete_polsn", function () {
            $(this).parent().parent().remove();
        });
        $('#purchaseOrderLinesContainer').on("click", ".f_add_polsn", function () {
            var input = $(this).parent().parent().find('input');
            var serialNumber = input.val();
            var pol_id = $(this).attr('pol_id');
            serialNumber = serialNumber.trim();
            input.focus();
            if (serialNumber.trim() == '')
            {
                return;
            }
            input.val('');

            var polSnRow = $('#purchaseOrderLineSerialNumberRowTemplate').clone();
            polSnRow.css({'display': 'table-row'});
            polSnRow.removeAttr('id');
            polSnRow.find('input').val(serialNumber);
            polSnRow.appendTo("#purchaseOrderLineSerialNumbersConteiner_" + pol_id);
        });
    },
    calculatePurchaseOrderLinesData: function () {
        var ret = [];
        $('.purchaseOrderLineSerialNumbers').each(function () {
            var pol_id = $(this).attr('pol_id');
            var pol_serial_numbers = [];
            $(this).find("input").each(function () {
                pol_serial_numbers.push($(this).val());
            });
            ret.push({'pol_id':pol_id, serial_numbers:pol_serial_numbers});
        });
        $('#pols_serial_numbers').val(JSON.stringify(ret));
        
    }
});
