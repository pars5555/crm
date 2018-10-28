NGS.createLoad("crm.loads.main.partner.list", {
    search_timout_handler: null,
    getContainer: function () {
        return "initialLoad";
    },
    onError: function (params) {

    },
    afterLoad: function () {
        $('#partnerFilters').find('input[name="st"]').keyup(function () {
            if (this.search_timout_handler > 0) {
                window.clearTimeout(this.search_timout_handler);
            }
            this.search_timout_handler = window.setTimeout(function () {
                $('#partnerFilters').trigger('submit');
            }, 1000);
        }.bind(this));
        $('#partnerFilters').find('select, checkbox').change(function () {
            $('#partnerFilters').trigger('submit');
        });
        $('.f_hidden_checkbox').change(function () {
            var partner_id = $(this).data('partner_id');
            var hidden = $(this).is(':checked') ? 1 : 0;

            NGS.action('crm.actions.main.partner.set_partner_hidden', {partner_id: partner_id, hidden: hidden});
        });
        $('.f_included_in_capital_checkbox').change(function () {
            var partner_id = $(this).data('partner_id');
            var included_in_capital = $(this).is(':checked') ? 1 : 0;

            NGS.action('crm.actions.main.partner.set_partner_included_in_capital', {partner_id: partner_id, included_in_capital: included_in_capital});
        });
        this.initExportCsv();
    },
    initExportCsv: function () {
        $('#export_csv').click(function () {
            var urlParams = $("#partnerFilters").serialize();
            var actionUrl = '/dyn/main_partner/do_export_csv?';
            $(this).attr('href', actionUrl + urlParams);
        });
    }
});
