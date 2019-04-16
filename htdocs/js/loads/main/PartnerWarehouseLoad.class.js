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
                theme: 'tooltipster-shadow',
                minWidth: 300
            });
        });
        $("#partner_select").chosen({
            search_contains: true
        });
        this.initPartnerSelect();
        this.initExport();
    },
    initExport: function () {
        $('#export_csv').click(function () {
            var partner_id = $('#partner_select').val();
            var actionUrl = '/dyn/main_warehouse/do_export_partner_csv?partner_id=' + partner_id;
            $(this).attr('href', actionUrl);
        });

    },
    initPartnerSelect: function () {
        $('#partner_select').change(function () {
            var partner_id = $(this).val();
            NGS.load('crm.loads.main.partner_warehouse', {partner_id: partner_id});
        });
    },

});
