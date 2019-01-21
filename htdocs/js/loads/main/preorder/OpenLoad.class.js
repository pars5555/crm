NGS.createLoad("crm.loads.main.preorder.open", {
    search_timout_handler:0,
    getContainer: function () {
        return "initialLoad";
    },
    onError: function (params) {

    },
    afterLoad: function () {

        var thisInstance = this;
        $('#submitForm').click(function () {
            thisInstance.calculatePreorderLinesData();
            $('#preorderLinesForm').trigger('submit');
        });
        this.initPreorderLineAddFunctionallity();
        this.initPreorderLineRemoveFunctionallity();
        this.initCancelPreorder();
        this.initPaidFunctionality();
        this.initSearch();

    },
    initSearch:function(){
        $('#search_item').keyup(function () {
            if (this.search_timout_handler > 0) {
                window.clearTimeout(this.search_timout_handler);
            }
            this.search_timout_handler = window.setTimeout(function () {
                $('.preorderLine').css({'background':''});
                var searchText = $('#search_item').val().toLowerCase();
                $('.preorderLine .preorderLinesSelectProduct').each(function(){
                    if ($(this).attr('title').toLowerCase().includes(searchText))
                    {
                        $(this).closest('.preorderLine').css({'background':'blue'});
                    }
                });
                $('#billingFilters').trigger('submit');
            }, 200);
        }.bind(this));
       
    },
    initPaidFunctionality: function () {
        $('#paidCheckbox').change(function () {
            var checked = $(this).is(':checked');
            var id = $('#preorder_id').val();
            NGS.action('crm.actions.main.preorder.set_paid', {'id': id, 'paid': checked ? 1 : 0});
        });
    },
    initPreorderLineRemoveFunctionallity: function () {
        $('#preorderLinesContainer').on("click", ".removePreorderLine", function () {
            $(this).closest('.preorderLine').remove();
        });
    },
    initCancelPreorder: function () {
        $('#cancelPreorderButton').click(function () {
            if (confirm("Are you sure you want to cancel the preorder?!"))
            {
                $(this).closest('form').trigger('submit');
            }
        });
    },
    initPreorderLineAddFunctionallity: function () {
        $('#addPreorderLineButton').click(function () {

            var product_id = $('#preorderLineProductId').val();
            var quantity = $('#preorderLineQuantity').val();
            var unit_price = $('#preorderLineUnitPrice').val();
            var currency_id = $('#preorderLineCurrencyId').val();
            if (product_id == 0)
            {
                return;
            }
            if (!(quantity > 0))
            {
                $('#preorderLineQuantity').focus();
                $("#preorderLineQuantity").css("display", "none").fadeIn(1000);
                return;
            }
            if (!(unit_price >= 0))
            {
                $('#preorderLineUnitPrice').focus();
                $("#preorderLineUnitPrice").css("display", "none").fadeIn(1000);
                return;
            }
            if (currency_id == 0)
            {
                $('#preorderLineCurrencyId').focus();
                return;
            }
            var polRow = $('#preorderLineTemplate').clone();


            $('#preorderLineProductId').val('0');
            $('#preorderLineProductId').trigger('chosen:updated');
            $('#preorderLineQuantity').val('');
            $('#preorderLineUnitPrice').val('');
            $('#preorderLineCurrencyId').val('0');

            polRow.css({'display': 'table-row'});
            polRow.removeAttr('id');
            polRow.addClass('preorderLine');

            polRow.find(".preorderLinesSelectProduct").val(product_id);
            polRow.find(".preorderLinesSelectQuantity").val(quantity);
            polRow.find(".preorderLinesSelectUnitPrice").val(unit_price);
            polRow.find(".preorderLinesSelectCurrency").val(currency_id);

            polRow.appendTo("#preorderLinesContainer");
            $(".preorderLine .preorderLinesSelectProduct").chosen();
        });
    },
    calculatePreorderLinesData: function () {
        $('.preorderLine').each(function () {
            var product_id = $(this).find(".preorderLinesSelectProduct").val();
            var quantity = $(this).find(".preorderLinesSelectQuantity").val();
            var unit_price = $(this).find(".preorderLinesSelectUnitPrice").val();
            var currency_id = $(this).find(".preorderLinesSelectCurrency").val();
            var data = {product_id: product_id, quantity: quantity, unit_price: unit_price, currency_id: currency_id};
            if (this.hasAttribute("line_id")) {
                data.line_id = $(this).attr("line_id");
            }
            var jsonData = JSON.stringify(data);
            $(this).find("input[type='hidden']").val(jsonData);

        });
    }
});
