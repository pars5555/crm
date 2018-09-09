NGS.createLoad("crm.loads.main.purse.list", {
    search_timout_handler: null,
    getContainer: function () {
        return "initialLoad";
    },
    onError: function (params) {

    },
    afterLoad: function () {
        $('#productFilters').find('input[name="st"]').keyup(function () {
            if (this.search_timout_handler > 0) {
                window.clearTimeout(this.search_timout_handler);
            }
            this.search_timout_handler = window.setTimeout(function () {
                $('#productFilters').trigger('submit');
            }, 1000);
        }.bind(this));
        $('#productFilters').find('select, checkbox').change(function () {
            $('#productFilters').trigger('submit');
        });

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
        this.initUpload();
        this.initUpdate();
        this.initHide();
    },
    initUpdate: function () {
        $('.f_update_purse').click(function () {
            var account = $(this).data('account_name');
            NGS.action('crm.actions.main.purse.update_orders', {account_name: account});
        });
    },
    initHide: function () {
        $('.f_hide').click(function () {
            var id = $(this).data('id');
            NGS.action('crm.actions.main.purse.set_hidden', {id: id, hide:1});
            $(this).closest('.table-row').remove();
        });
    },
    initUpload: function () {
        jQuery('#upload_button').click(function () {
            jQuery("#file_input").trigger('click');
            return false;
        });

        jQuery("#file_input").change(function () {
            jQuery('#upload_form').submit();
        });
    }
});
function uploadedFileResponse(changedOrNewOrdersText) {
    if (changedOrNewOrdersText.length > 0) {
        window.location.href = "/purse/list?changed_orders=" + changedOrNewOrdersText;
    } else
    {
        window.location.href = "/purse/list";

    }

}
;
