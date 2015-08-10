NGS.createLoad("crm.loads.main.payment.list", {
    getContainer: function () {
        return "initialLoad";
    },
    onError: function (params) {

    },
    afterLoad: function () {
        $('#paymentFilters').find('input, select, checkbox').change(function () {
            $('#paymentFilters').trigger('submit');
        });
        $('.deletePayment').click(function () {
            if (confirm("Are you sure you want to delete this Payment Order?!"))
            {
                return true;
            }
            return false;
        });
    }
});
