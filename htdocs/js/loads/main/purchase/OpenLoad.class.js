NGS.createLoad("crm.loads.main.purchase.open", {
    getContainer: function () {
        return "initialLoad";
    },
    onError: function (params) {

    },
    afterLoad: function () {

        var thisInstance = this;
        $('#submitForm').click(function () {
            thisInstance.calculatePurchaseOrderLinesData();
            $('#purchaseOrderLinesForm').trigger('submit');
        });
        this.initPurchaseOrderLineAddFunctionallity();
        this.initCancelPurchaseOrder();

    },
    initCancelPurchaseOrder: function () {
        $('#cancelPurchaseOrderButton').click(function () {
            if (confirm("Are you sure you want to cancel the Purchase Order?!"))
            {
                $(this).parent('form').trigger('submit');
            }
        });
    },
    initPurchaseOrderLineAddFunctionallity: function () {
        $('#addPurchaseOrderLineButton').click(function () {

            var product_id = $('#purchaseOrderLineProductId').val();
            var quantity = $('#purchaseOrderLineQuantity').val();
            var unit_price = $('#purchaseOrderLineUnitPrice').val();
            var currency_id = $('#purchaseOrderLineCurrencyId').val();
            if (product_id == 0)
            {
                $('#purchaseOrderLineProductId').focus();
                $("#purchaseOrderLineProductId").css("display", "none").fadeIn(1000);
                return;
            }
            if (!(quantity > 0))
            {
                $('#purchaseOrderLineQuantity').focus();
                $("#purchaseOrderLineQuantity").css("display", "none").fadeIn(1000);
                return;
            }
            if (!(unit_price > 0))
            {
                $('#purchaseOrderLineUnitPrice').focus();
                $("#purchaseOrderLineUnitPrice").css("display", "none").fadeIn(1000);
                return;
            }
            if (currency_id == 0)
            {
                $('#purchaseOrderLineCurrencyId').focus();
                $("#purchaseOrderLineCurrencyId").css("display", "none").fadeIn(1000);
                return;
            }
            var polRow = $('#purchaseOrderLineTemplate').clone();


            $('#purchaseOrderLineProductId').val('0');
            $('#purchaseOrderLineQuantity').val('');
            $('#purchaseOrderLineUnitPrice').val('');
            $('#purchaseOrderLineCurrencyId').val('0');

            polRow.css({'display': 'table-row'});
            polRow.removeAttr('id');
            polRow.addClass('purchaseOrderLine');

            polRow.find(".purchaseOrderLinesSelectProduct").val(product_id);
            polRow.find(".purchaseOrderLinesSelectQuantity").val(quantity);
            polRow.find(".purchaseOrderLinesSelectUnitPrice").val(unit_price);
            polRow.find(".purchaseOrderLinesSelectCurrency").val(currency_id);

            polRow.appendTo("#purchaseOrderLinesContainer");

        });
    },
    calculatePurchaseOrderLinesData: function () {
        $('.purchaseOrderLine').each(function () {
            var product_id = $(this).find(".purchaseOrderLinesSelectProduct").val();
            var quantity = $(this).find(".purchaseOrderLinesSelectQuantity").val();
            var unit_price = $(this).find(".purchaseOrderLinesSelectUnitPrice").val();
            var currency_id = $(this).find(".purchaseOrderLinesSelectCurrency").val();
            var data = {product_id: product_id, quantity: quantity, unit_price: unit_price, currency_id: currency_id};
            var jsonData = JSON.stringify(data);
            $(this).find("input[type='hidden']").val(jsonData);

        });
    }
});
