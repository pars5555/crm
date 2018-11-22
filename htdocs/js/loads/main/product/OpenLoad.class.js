NGS.createLoad("crm.loads.main.product.open", {
    getContainer: function () {
        return "initialLoad";
    },
    onError: function (params) {

    },
    afterLoad: function () {
        $('.deleteProductButton').click(function () {
            if (confirm("Are you sure you want to delete product?"))
            {
                return true;
            }
            return false;
        });
        $('.mergeAndDeleteProductButton').click(function () {
            var id = $(this).data('id');
            NGS.load('crm.loads.main.product.prepare_merge', {id: id});
        });
    }
});
