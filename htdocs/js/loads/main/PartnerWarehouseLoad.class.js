NGS.createLoad("crm.loads.main.partner_warehouse", {
    getContainer: function () {
        return "indexRightContent";
    },
    onError: function (params) {

    },
    afterLoad: function () {
        $('.tooltipster').each(function () {
            var content = $(this).find('p').html();
            $(this).tooltipster({
                content: $(content),
                animation: 'fade',
                trigger: 'hover',
                contentAsHTML: true,
                interactive: true,
                theme: 'tooltipster-shadow'
            });
        });
        $("#partner_select").chosen({
            search_contains: true
        });
        this.initPartnerSelect();
    },
    initPartnerSelect: function () {
        $('#partner_select').change(function () {
            var partner_id = $(this).val();
            NGS.load('crm.loads.main.partner_warehouse', {partner_id: partner_id});
        });
    },

});
