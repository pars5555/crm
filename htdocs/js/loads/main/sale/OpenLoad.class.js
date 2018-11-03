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
        this.calculateTotal();
        this.initSearch();
        this.initNonProfitFunctionality();
        $('#saleOrderLinesForm').on('change', 'input, select, checkbox', function () {
            this.calculateTotal();
        }.bind(this));
    },
     initSearch:function(){
        $('#search_item').keyup(function () {
            if (this.search_timout_handler > 0) {
                window.clearTimeout(this.search_timout_handler);
            }
            this.search_timout_handler = window.setTimeout(function () {
                $('.saleOrderLine').css({'background':''});
                var searchText = $('#search_item').val().toLowerCase();
                $('.saleOrderLine .saleOrderLinesSelectProduct').each(function(){
                    if ($(this).attr('title').toLowerCase().includes(searchText))
                    {
                        $(this).closest('.saleOrderLine').css({'background':'blue'});
                    }
                });
                $('#billingFilters').trigger('submit');
            }, 200);
        }.bind(this));
       
    },
    calculateTotal: function () {
        var grandTotal = {};
        $('.saleOrderLine').each(function () {
            var qty = $(this).find('.saleOrderLinesSelectQuantity').val();
            var unitPrice = $(this).find('.saleOrderLinesSelectUnitPrice').val();
            var currencySelectBox = $(this).find('.saleOrderLinesSelectCurrency');
            var selectedCurrencyOption = $('option:selected', currencySelectBox);
            var position = selectedCurrencyOption.attr('position');
            var iso = selectedCurrencyOption.attr('iso');
            var symbol = selectedCurrencyOption.attr('symbol');
            var total = qty * unitPrice;
            if (!grandTotal[iso])
            {
                grandTotal[iso] = 0;
            }
            grandTotal[iso] += total;
            $(this).find('.saleOrderLinesTotal').text((position === 'left' ? symbol : '') + total.toFixed(2) + (position === 'right' ? symbol : ''));
        });
        totalHtml = "";
        $.each(grandTotal, function (index, val) {
            totalHtml += '<div>' + index + ':' + val + '</div>';
        });
        $('#saleOrderTotalAmount').html(totalHtml);
    },
    initNonProfitFunctionality: function () {
        $('#nonProfitCheckbox').change(function () {
            var checked = $(this).is(':checked');
            var id = $('#sale_order_id').val();
            NGS.action('crm.actions.main.sale.set_non_profit', {'id': id, 'non_profit': checked ? 1 : 0});
        });
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
                $(this).closest('form').trigger('submit');
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
                return;
            }
            if (!(quantity > 0))
            {
                $('#saleOrderLineQuantity').focus();
                $("#saleOrderLineQuantity").css("display", "none").fadeIn(1000);
                return;
            }
            if (!(unit_price >= 0))
            {
                $('#saleOrderLineUnitPrice').focus();
                $("#saleOrderLineUnitPrice").css("display", "none").fadeIn(1000);
                return;
            }
            if (currency_id == 0)
            {
                $('#saleOrderLineCurrencyId').focus();
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
            if (this.hasAttribute("line_id")) {
                data.line_id = $(this).attr("line_id");
            }
            var jsonData = JSON.stringify(data);
            $(this).find("input[type='hidden']").val(jsonData);

        });
    }
});
