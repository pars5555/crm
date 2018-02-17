NGS.createLoad("crm.loads.main.rorder.open", {
    getContainer: function () {
        return "initialLoad";
    },
    onError: function (params) {

    },
    afterLoad: function () {
        var thisInstance = this;
        $('#submitForm').click(function () {
            thisInstance.calculaterecipientOrderLinesData();
            $('#recipientOrderLinesForm').trigger('submit');
        });
        this.initRecipientOrderLineAddFunctionallity();
        this.initRecipientOrderLineRemoveFunctionallity();
        this.initCancelrecipientOrder();
        this.initPaidFunctionality();

    },
    initPaidFunctionality: function () {
        $('#paidCheckbox').change(function () {
            var checked = $(this).is(':checked');
            var id = $('#recipient_order_id').val();
            NGS.action('crm.actions.main.rorder.set_paid', {'id': id, 'paid': checked ? 1 : 0});
        });
    },
    initRecipientOrderLineRemoveFunctionallity: function () {
        $('#recipientOrderLinesContainer').on("click", ".removerecipientOrderLine", function () {
            $(this).closest('.recipientOrderLine').remove();
        });
    },
    initCancelrecipientOrder: function () {
        $('#cancelRecipientOrderButton').click(function () {
            if (confirm("Are you sure you want to cancel the Order?!"))
            {
                $(this).parent('form').trigger('submit');
            }
        });
    },
    initRecipientOrderLineAddFunctionallity: function () {
        $('#addRecipientOrderLineButton').click(function () {

            var product_id = $('#recipientOrderLineProductId').val();
            var quantity = $('#recipientOrderLineQuantity').val();
            var unit_price = $('#recipientOrderLineUnitPrice').val();
            var currency_id = $('#recipientOrderLineCurrencyId').val();
            if (product_id == 0)
            {
                return;
            }
            if (!(quantity > 0))
            {
                $('#recipientOrderLineQuantity').focus();
                $("#recipientOrderLineQuantity").css("display", "none").fadeIn(1000);
                return;
            }
            if (!(unit_price >= 0))
            {
                $('#recipientOrderLineUnitPrice').focus();
                $("#recipientOrderLineUnitPrice").css("display", "none").fadeIn(1000);
                return;
            }
            if (currency_id == 0)
            {
                $('#recipientOrderLineCurrencyId').focus();
                return;
            }
            var polRow = $('#recipientOrderLineTemplate').clone();


            $('#recipientOrderLineProductId').val('0');
            $('#recipientOrderLineProductId').trigger('chosen:updated');
            $('#recipientOrderLineQuantity').val('');
            $('#recipientOrderLineUnitPrice').val('');
            $('#recipientOrderLineCurrencyId').val('0');

            polRow.css({'display': 'table-row'});
            polRow.removeAttr('id');
            polRow.addClass('recipientOrderLine');

            polRow.find(".recipientOrderLinesSelectProduct").val(product_id);
            polRow.find(".recipientOrderLinesSelectQuantity").val(quantity);
            polRow.find(".recipientOrderLinesSelectUnitPrice").val(unit_price);
            polRow.find(".recipientOrderLinesSelectCurrency").val(currency_id);

            polRow.appendTo("#recipientOrderLinesContainer");
            $(".recipientOrderLine .recipientOrderLinesSelectProduct").chosen();
        });
    },
    calculaterecipientOrderLinesData: function () {
        $('.recipientOrderLine').each(function () {
            var product_id = $(this).find(".recipientOrderLinesSelectProduct").val();
            var quantity = $(this).find(".recipientOrderLinesSelectQuantity").val();
            var unit_price = $(this).find(".recipientOrderLinesSelectUnitPrice").val();
            var currency_id = $(this).find(".recipientOrderLinesSelectCurrency").val();
            var data = {product_id: product_id, quantity: quantity, unit_price: unit_price, currency_id: currency_id};
            if (this.hasAttribute("line_id")) {
                data.line_id = $(this).attr("line_id");
            }
            var jsonData = JSON.stringify(data);
            $(this).find("input[type='hidden']").val(jsonData);

        });
    }
});
