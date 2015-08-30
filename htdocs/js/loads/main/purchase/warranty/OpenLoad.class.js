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

        this.initAddPolSnLine();
    },
    initAddPolSnLine: function () {
        $('#purchaseOrderLinesContainer').on("click", ".f_add_polsn", function () {
            var serialNumber = $('#purchaseOrderLineSerialNumber').find('input').val();
            serialNumber = serialNumber.trim();
            if (serialNumber.trim() == '')
            {
                return;
            }
            var polSnRow = $('#purchaseOrderLineSerialNumberRowTemplate').clone();

            polSnRow.css({'display': 'table-row'});
            polSnRow.removeAttr('id');
            polSnRow.addClass('purchaseOrderLineSerialNumbers');
            polSnRow.appendTo("#purchaseOrderLinesContainer");
        });
    },
    calculatePurchaseOrderLinesData: function () {
        $('.purchaseOrderLineSerialNumbers').each(function () {
            var serial_number = $(this).find(".purchaseOrderLineSerialNumber").val();
            var data = {product_id: product_id, quantity: quantity, unit_price: unit_price, currency_id: currency_id};
            var jsonData = JSON.stringify(data);
            $(this).find("input[type='hidden']").val(jsonData);

        });
    }
});
