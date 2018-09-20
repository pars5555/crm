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
        this.initTrackingsSearch();
        this.initExportTrackingsSearch();
        this.initHideByTrackings();
        this.initHideByTrackingsConfirm();
        this.initRefreshTracking();
    },
    initExportTrackingsSearch: function(){
        $('#export_search').click(function(){
            var  trackingNumbers = $('#tracking_numbers_input').val();
            var params = {trackingNumbers :trackingNumbers};
            var urlParams = $.param(params);
            var actionUrl = '/dyn/main_purse/do_export_search_csv?';
            $(this).attr('href', actionUrl + urlParams);
        });
    },
    initTrackingsSearch: function(){
        $('#find_trackings_button').click(function(){
            $('#trackings_modalBox').addClass('is_active');
        });
    },
    initHideByTrackingsConfirm: function(){
        $('#hide_by_trackings_confirm').click(function(){
            var  trackingNumbers = $('#hide_by_trackings_input').val();
            var params = {trackingNumbers :trackingNumbers};
            NGS.action('crm.actions.main.purse.hide_by_trackings', params);
        });
    },
    initHideByTrackings: function(){
        $('#hide_by_trackings_button').click(function(){
            $('#hide_by_trackings_modalBox').addClass('is_active');
        });
        
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
    initRefreshTracking: function () {
        $('.f_refresh_tracking').click(function () {
            var id = $(this).data('id');
            NGS.action('crm.actions.main.purse.refresh_tracking', {id: id});
            $(this).closest('.f_tracking').html('');
        });
    },
    initRefreshCarrierDeliveryDate: function () {
        $('.f_refresh_carrier_delivery_details').click(function () {
            var id = $(this).data('id');
            NGS.action('crm.actions.main.purse.refresh_carrier_delivery_details', {id: id});
            $(this).closest('.f_carrier_delivery_details').html('');
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
