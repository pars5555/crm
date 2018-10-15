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
        $('#productFilters').find('select').change(function () {
            $('#productFilters').trigger('submit');
        });
        $('#productFilters').find('input[name="pr"]').change(function () {
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
        this.initProblematic();
        this.initSolveProblematic();
        this.initNotRegTrackings();
        this.initTrackingsSearch();
        this.initExportTrackingsSearch();
        this.initHideByTrackings();
        this.initHideByTrackingsConfirm();
        this.initNotRegTrackingsConfirm();
        this.initRefreshTracking();
        this.initRefreshCarrierDeliveryDate();
        this.initAddExternalOrder();
        this.initAddExternalOrderConfirm();
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
    initAddExternalOrder: function(){
        $('#add_external_order_button').click(function(){
            $('#add_external_order_modalBox').addClass('is_active');
        });
    },
    initAddExternalOrderConfirm: function(){
        $('#add_external_order_confirm').click(function(){
            var  unit_address = $('#external_order_unit_address_input').val();
            var  url = $('#external_order_url_input').val();
            var  qty = $('#external_order_qty_input').val();
            var  price = $('#external_order_price_input').val();
            var params = {url:url, qty:qty, price:price, unit_address:unit_address};
            NGS.action('crm.actions.main.purse.add_external_order', params);
            $(this).remove();
        });
    },
    initHideByTrackingsConfirm: function(){
        $('#hide_by_trackings_confirm').click(function(){
            var  trackingNumbers = $('#hide_by_trackings_input').val();
            var params = {trackingNumbers :trackingNumbers};
            NGS.action('crm.actions.main.purse.hide_by_trackings', params);
        });
    },
    initNotRegTrackingsConfirm: function(){
        $('#not_registered_trackings_confirm').click(function(){
            var  trackingNumbers = $('#not_registered_trackings_input').val();
            window.location.href = "/purse/list?roiw=" + trackingNumbers;
        });
    },
    initHideByTrackings: function(){
        $('#hide_by_trackings_button').click(function(){
            $('#hide_by_trackings_modalBox').addClass('is_active');
        });
        
    },
    initNotRegTrackings: function(){
        $('#not_registered_trackings_button').click(function(){
            $('#not_registered_trackings_modalBox').addClass('is_active');
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
            $(this).closest('tr').remove();
        });
    },
    initProblematic: function () {
        $('.f_problematic').click(function () {
            var id = $(this).data('id');
            NGS.action('crm.actions.main.purse.set_problematic', {id: id});
            
        });
    },
    initSolveProblematic: function () {
        $('.f_problem_solved').click(function () {
            var id = $(this).data('id');
            NGS.action('crm.actions.main.purse.set_problem_solved', {id: id});
            
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
            $(this).remove();
            $('#carrier_delivery_details_'+id).html('');
            $('#carrier_tracking_status_'+id).html('');
            
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
