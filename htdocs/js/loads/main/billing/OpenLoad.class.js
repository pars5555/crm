NGS.createLoad("crm.loads.main.billing.open", {
    getContainer: function () {
        return "initialLoad";
    },
    onError: function (params) {

    },
    afterLoad: function () {
       $('#cancelBillingButton').click(function () {
            if (confirm("Are you sure you want to cancel the billing?!"))
            {
                $(this).parent('form').trigger('submit');
            }
        });
    }
});
