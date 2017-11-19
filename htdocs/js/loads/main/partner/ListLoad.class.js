NGS.createLoad("crm.loads.main.partner.list", {
    getContainer: function () {
        return "initialLoad";
    },
    onError: function (params) {

    },
    afterLoad: function () {
        $('#partnerFilters').find('input, select, checkbox').change(function () {
            $('#partnerFilters').trigger('submit');
        });
        $('.f_hidden_checkbox').change(function () {
            var partner_id = $(this).data('partner_id');
            var hidden = $(this).is(':checked') ? 1 : 0;

            NGS.action('crm.actions.main.partner.set_partner_hidden', {partner_id: partner_id, hidden: hidden});
        });
    }
});
