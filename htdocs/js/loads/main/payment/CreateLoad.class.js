NGS.createLoad("crm.loads.main.payment.create", {
    getContainer: function () {
        return "initialLoad";
    },
    onError: function (params) {

    },
    afterLoad: function () {
        this.initSignature();
    },
    initSignature: function () {
        $("#signatureContainer").jSignature();
        try {
            var signatureData = $.parseJSON($("#signatureContainer").find('span').text());
            $("#signatureContainer").jSignature('setData', signatureData, 'native');
        } catch (e) {
        }
        $(".clearSignature").click(function () {
            $("#signatureContainer").jSignature('reset');
        });
        $('.createPaymentOrder').submit(function () {
            var signatureData = $("#signatureContainer").jSignature('getData', 'native');
            $("#signature").val(JSON.stringify(signatureData));
            return true;
        });
    }
});
