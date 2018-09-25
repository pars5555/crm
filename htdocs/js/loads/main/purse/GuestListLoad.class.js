NGS.createLoad("crm.loads.main.purse.guest_list", {
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
        this.initHide();
        this.initTrackingsSearch();
        this.initHideByTrackingsConfirm();
        this.initRefreshTracking();
        this.initRefreshCarrierDeliveryDate();
    },   
    initTrackingsSearch: function(){
        $('#find_trackings_button').click(function(){
            $('#trackings_modalBox').addClass('is_active');
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
            $(this).remove();
            $('#carrier_delivery_details_'+id).html('');
            $('#carrier_tracking_status_'+id).html('');
            
        });
    }
});