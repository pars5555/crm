NGS.createLoad("crm.loads.main.billing.create", {
    getContainer: function () {
        return "initialLoad";
    },
    onError: function (params) {

    },
    afterLoad: function () {
        $("select[name='partnerId']").chosen();

        $("select[name='partnerId']").change(function () {
            var partner_id = $(this).val();
            NGS.action('crm.actions.main.partner.get_partner_dept', {partner_id: partner_id});
        });



    }
});
