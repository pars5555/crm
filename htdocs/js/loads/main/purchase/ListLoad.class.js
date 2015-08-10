NGS.createLoad("crm.loads.main.purchase.list", {
    getContainer: function () {
        return "initialLoad";
    },
    onError: function (params) {

    },
    afterLoad: function () {
        $('#purchaseOrderFilters').find('input, select, checkbox').change(function () {
            $('#purchaseOrderFilters').trigger('submit');
        });
        $('.deletePurchaseOrder').click(function () {
            if (confirm("Are you sure you want to delete this Purchase Order?!"))
            {
                return true;
            }
            return false;
        });
    }
});
