NGS.createAction("crm.actions.main.product.set_product_qty_checked", {
    onError: function (res) {
        alert(res.msg);
    },
    afterAction: function (transport) {
    }
});
