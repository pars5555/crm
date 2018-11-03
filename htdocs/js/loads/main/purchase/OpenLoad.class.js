NGS.createLoad("crm.loads.main.purchase.open", {
    search_timout_handler:0,
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
        this.initPurchaseOrderLineRemoveFunctionallity();
        this.initCancelPurchaseOrder();
        this.initPaidFunctionality();
        this.initSearch();

    },
    initSearch:function(){
        $('#search_item').keyup(function () {
            if (this.search_timout_handler > 0) {
                window.clearTimeout(this.search_timout_handler);
            }
            this.search_timout_handler = window.setTimeout(function () {
                $('.purchaseOrderLine').css({'background':''});
                var searchText = $('#search_item').val().toLowerCase();
                $('.purchaseOrderLine .purchaseOrderLinesSelectProduct').each(function(){
                    if ($(this).attr('title').toLowerCase().includes(searchText))
                    {
                        $(this).closest('.purchaseOrderLine').css({'background':'blue'});
                    }
                });
                $('#billingFilters').trigger('submit');
            }, 200);
        }.bind(this));
       
    },
    initPaidFunctionality: function () {
        $('#paidCheckbox').change(function () {
            var checked = $(this).is(':checked');
            var id = $('#purchase_order_id').val();
            NGS.action('crm.actions.main.purchase.set_paid', {'id': id, 'paid': checked ? 1 : 0});
        });
    },
    initPurchaseOrderLineRemoveFunctionallity: function () {
        $('#purchaseOrderLinesContainer').on("click", ".removePurchaseOrderLine", function () {
            $(this).closest('.purchaseOrderLine').remove();
        });
    },
    initCancelPurchaseOrder: function () {
        $('#cancelPurchaseOrderButton').click(function () {
            if (confirm("Are you sure you want to cancel the Purchase Order?!"))
            {
                $(this).closest('form').trigger('submit');
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
                return;
            }
            if (!(quantity > 0))
            {
                $('#purchaseOrderLineQuantity').focus();
                $("#purchaseOrderLineQuantity").css("display", "none").fadeIn(1000);
                return;
            }
            if (!(unit_price >= 0))
            {
                $('#purchaseOrderLineUnitPrice').focus();
                $("#purchaseOrderLineUnitPrice").css("display", "none").fadeIn(1000);
                return;
            }
            if (currency_id == 0)
            {
                $('#purchaseOrderLineCurrencyId').focus();
                return;
            }
            var polRow = $('#purchaseOrderLineTemplate').clone();


            $('#purchaseOrderLineProductId').val('0');
            $('#purchaseOrderLineProductId').trigger('chosen:updated');
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
            $(".purchaseOrderLine .purchaseOrderLinesSelectProduct").chosen();
        });
    },
    calculatePurchaseOrderLinesData: function () {
        $('.purchaseOrderLine').each(function () {
            var product_id = $(this).find(".purchaseOrderLinesSelectProduct").val();
            var quantity = $(this).find(".purchaseOrderLinesSelectQuantity").val();
            var unit_price = $(this).find(".purchaseOrderLinesSelectUnitPrice").val();
            var currency_id = $(this).find(".purchaseOrderLinesSelectCurrency").val();
            var data = {product_id: product_id, quantity: quantity, unit_price: unit_price, currency_id: currency_id};
            if (this.hasAttribute("line_id")) {
                data.line_id = $(this).attr("line_id");
            }
            var jsonData = JSON.stringify(data);
            $(this).find("input[type='hidden']").val(jsonData);

        });
    }
});
