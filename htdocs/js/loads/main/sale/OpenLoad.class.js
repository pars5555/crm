NGS.createLoad("crm.loads.main.sale.open", {
    getContainer: function () {
        return "initialLoad";
    },
    onError: function (params) {

    },
    afterLoad: function () {

        var thisInstance = this;
        $('#submitForm').click(function () {
            thisInstance.calculateSaleOrderLinesData();
            $('#saleOrderLinesForm').trigger('submit');
        });
        this.initSaleOrderLineAddFunctionallity();
        this.initSaleOrderLineRemoveFunctionallity();
        this.initCancelSaleOrder();
        this.initBilledFunctionality();

    },
    initBilledFunctionality: function () {
        $('#billedCheckbox').change(function () {
            var checked = $(this).is(':checked');
            var id = $('#sale_order_id').val();
            NGS.action('crm.actions.main.sale.set_billed', {'id': id, 'billed': checked ? 1 : 0});
        });
    },
    initCancelSaleOrder: function () {
        $('#cancelSaleOrderButton').click(function () {
            if (confirm("Are you sure you want to cancel the Sale Order?!"))
            {
                $(this).parent('form').trigger('submit');
            }
        });
    },
    initSaleOrderLineRemoveFunctionallity: function () {
        $('#saleOrderLinesContainer').on("click", ".removeSaleOrderLine", function () {
            $(this).closest('.saleOrderLine').remove();
        });
    },
    initSaleOrderLineAddFunctionallity: function () {
        $('#saleOrderLineProductId').change(function () {
            var product_id = $('#saleOrderLineProductId').val();
            NGS.action('crm.actions.main.sale.get_product_count', {product_id: product_id});
        });

        $('#addSaleOrderLineButton').click(function () {

            var product_id = $('#saleOrderLineProductId').val();
            var quantity = $('#saleOrderLineQuantity').val();
            var unit_price = $('#saleOrderLineUnitPrice').val();
            var currency_id = $('#saleOrderLineCurrencyId').val();
            if (product_id == 0)
            {
                $('#saleOrderLineProductId').focus();
                $("#saleOrderLineProductId").css("display", "none").fadeIn(1000);
                return;
            }
            if (!(quantity > 0))
            {
                $('#saleOrderLineQuantity').focus();
                $("#saleOrderLineQuantity").css("display", "none").fadeIn(1000);
                return;
            }
            if (!(unit_price > 0))
            {
                $('#saleOrderLineUnitPrice').focus();
                $("#saleOrderLineUnitPrice").css("display", "none").fadeIn(1000);
                return;
            }
            if (currency_id == 0)
            {
                $('#saleOrderLineCurrencyId').focus();
                $("#saleOrderLineCurrencyId").css("display", "none").fadeIn(1000);
                return;
            }
            NGS.action('crm.actions.main.sale.check_product_count_to_add_sale_order_line', {product_id: product_id, quantity: quantity});


        });
    },
    calculateSaleOrderLinesData: function () {
        $('.saleOrderLine').each(function () {
            var product_id = $(this).find(".saleOrderLinesSelectProduct").val();
            var quantity = $(this).find(".saleOrderLinesSelectQuantity").val();
            var unit_price = $(this).find(".saleOrderLinesSelectUnitPrice").val();
            var currency_id = $(this).find(".saleOrderLinesSelectCurrency").val();
            var data = {product_id: product_id, quantity: quantity, unit_price: unit_price, currency_id: currency_id};
            var jsonData = JSON.stringify(data);
            $(this).find("input[type='hidden']").val(jsonData);

        });
    }
});
