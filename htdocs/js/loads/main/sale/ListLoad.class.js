NGS.createLoad("crm.loads.main.sale.list", {
    getContainer: function () {
        return "initialLoad";
    },
    onError: function (params) {

    },
    afterLoad: function () {
        $('#saleOrderFilters').find('input, select, checkbox').not(".text_autocomplete").change(function () {
            $('#saleOrderFilters').trigger('submit');
        });
        $('.deleteSaleOrder').click(function () {
            if (confirm("Are you sure you want to delete this Sale Order?!"))
            {
                return true;
            }
            return false;
        });
    }
});
