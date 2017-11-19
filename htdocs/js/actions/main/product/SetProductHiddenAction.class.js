NGS.createAction("crm.actions.main.product.set_product_hidden", {
    onError: function (res) {
        alert(res.msg);
    },
    afterAction: function (transport) {
    }
});
