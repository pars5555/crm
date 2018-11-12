NGS.createLoad("crm.loads.main.sale.product_warehouses", {
    getContainer: function () {
        return "product_warehouses_container";
    },
    onError: function (params) {

    },
    afterLoad: function () {
        alert(1);
    }
});
