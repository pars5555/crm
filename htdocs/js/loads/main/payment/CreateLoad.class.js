NGS.createLoad("crm.loads.main.payment.create", {
    getContainer: function () {
        return "initialLoad";
    },
    onError: function (params) {

    },
    afterLoad: function () {
        $("#signatureContainer").jSignature();
        $('.createPaymentOrder').submit(function () {
            var signatureData = $("#signatureContainer").jSignature('getData', 'native');
            $("#signature").val(JSON.stringify(signatureData));
            return true;
        });
    }
});
