NGS.createLoad("crm.loads.main.product.open", {
    getContainer: function () {
        return "initialLoad";
    },
    onError: function (params) {

    },
    afterLoad: function () {
        $('#deleteProductButton').click(function () {
            if (confirm("Are you sure you want to delete product?"))
            {
                $(this).parent('form').trigger('submit');
            }
        });
    }
});
