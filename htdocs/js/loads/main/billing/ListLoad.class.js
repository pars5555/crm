NGS.createLoad("crm.loads.main.billing.list", {
    getContainer: function () {
        return "initialLoad";
    },
    onError: function (params) {

    },
    afterLoad: function () {
        $('#billingFilters').find('input, select, checkbox').change(function () {
            $('#billingFilters').trigger('submit');
        });
        $('.deleteBilling').click(function () {
            if (confirm("Are you sure you want to delete this Billing Order?"))
            {
                return true;
            }
            return false;
        });
    }
});
