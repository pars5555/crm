NGS.createAction("crm.actions.main.purse.refresh_carrier_delivery_details", {
    onError: function (res) {
    },
    afterAction: function (res) {
        
        $('#carrier_delivery_details_' + res.id).html(res.delivery_date);
        $('#carrier_tracking_status_' + res.id).html(res.status);
    }
});
