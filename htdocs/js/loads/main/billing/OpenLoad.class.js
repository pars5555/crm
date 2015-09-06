NGS.createLoad("crm.loads.main.billing.open", {
    getContainer: function () {
        return "initialLoad";
    },
    onError: function (params) {

    },
    afterLoad: function () {
        this.initSignature();
        $('#cancelBillingButton').click(function () {
            if (confirm("Are you sure you want to cancel the billing?!"))
            {
                $(this).parent('form').trigger('submit');
            }
        });
    },
    initSignature: function () {
        $("#signatureContainer").jSignature();
        var signatureData = $.parseJSON($("#signatureContainer").find('span').text());
        $("#signatureContainer").jSignature('setData', signatureData, 'native');
        var base64png = 'data:' + $("#signatureContainer").jSignature('getData', 'image');
        $("#signature").attr('src', base64png);
    }
});
