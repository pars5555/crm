NGS.createAction("crm.actions.main.sale.get_product_count", {
    onError: function (res) {
        $('#saleOrderLineErrorMessage').text(res.msg);
    },
    afterAction: function (transport) {
        $('#saleOrderLineProductStockCount').text(transport.params.quantity);
    }
});
