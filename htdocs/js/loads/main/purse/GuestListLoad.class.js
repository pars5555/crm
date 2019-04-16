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
                minWidth: 300,
                theme: 'tooltipster-shadow'
            });
        });       
      
        this.initTrackingsSearch();
        
        this.initRefreshTracking();
        this.initRefreshCarrierDeliveryDate();
    },   
    initTrackingsSearch: function(){
        $('#find_trackings_button').click(function(){
            $('#trackings_modalBox').addClass('is_active');
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