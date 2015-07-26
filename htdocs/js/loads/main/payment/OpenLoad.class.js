NGS.createLoad("crm.loads.main.payment.open", {
    getContainer: function () {
        return "initialLoad";
    },
    onError: function (params) {

    },
    afterLoad: function () {
       $('#cancelPaymentButton').click(function () {
            if (confirm("Are you sure you want to cancel the payment?!"))
            {
                $(this).parent('form').trigger('submit');
            }
        });
    }
});
