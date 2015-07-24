NGS.createLoad("crm.loads.main.payment", {
    getContainer: function () {
        return "initialLoad";
    },
    onError: function (params) {

    },
    afterLoad: function () {
        $('#cancellPaymentButton').click(function () {
            if (confirm("Are you sure you want to cancell the payment?!"))
            {
                $(this).parent('form').trigger('submit');
            }
        });
    }
});
