NGS.createLoad("crm.loads.main.billing.update", {
    getContainer: function () {
        return "initialLoad";
    },
    onError: function (params) {

    },
    afterLoad: function () {
        $("select[name='partnerId']").chosen();
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
        $('.updateBillingOrder').submit(function () {
            var signatureData = $("#signatureContainer").jSignature('getData', 'native');
            $("#signature").val(JSON.stringify(signatureData));
            return true;
        });
    }
});
