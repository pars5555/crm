NGS.createAction("crm.actions.main.check_product_count_to_add_sale_order_line", {
    onError: function (res) {
        $('#saleOrderLineErrorMessage').text(res.msg);
    },
    afterAction: function (transport) {
        var solRow = $('#saleOrderLineTemplate').clone();

        var product_id = $('#saleOrderLineProductId').val();
        var quantity = $('#saleOrderLineQuantity').val();
        var unit_price = $('#saleOrderLineUnitPrice').val();
        var currency_id = $('#saleOrderLineCurrencyId').val();

        $('#saleOrderLineProductId').val('0');
        $('#saleOrderLineQuantity').val('');
        $('#saleOrderLineUnitPrice').val('');
        $('#saleOrderLineCurrencyId').val('0');

        solRow.css({'display': 'table-row'});
        solRow.removeAttr('id');
        solRow.addClass('saleOrderLine');

        solRow.find(".saleOrderLinesSelectProduct").val(product_id);
        solRow.find(".saleOrderLinesSelectQuantity").val(quantity);
        solRow.find(".saleOrderLinesSelectUnitPrice").val(unit_price);
        solRow.find(".saleOrderLinesSelectCurrency").val(currency_id);

        solRow.appendTo("#saleOrderLinesContainer");
    }
});
