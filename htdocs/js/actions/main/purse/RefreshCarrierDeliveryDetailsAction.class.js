NGS.createAction("crm.actions.main.purse.refresh_carrier_delivery_details", {
    onError: function (res) {
    },
    afterAction: function (res) {
        
        $('#tracking_' + res.id).html(html);
    }
});
