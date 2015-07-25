NGS.createLoad("crm.loads.main.payments", {
    getContainer: function () {
        return "initialLoad";
    },
    onError: function (params) {

    },
    afterLoad: function () {
        $('#createPaymentButton').click(function () {
            if ($('.createPaymentOrder').hasClass('hide'))
            {
                $('.createPaymentOrder').removeClass('hide');
            }
        });
        $('#cancelPaymentButton').click(function () {
            if (!$('.createPaymentOrder').hasClass('hide'))
            {
                $('.createPaymentOrder').addClass('hide');
            }
        });
    }
});
