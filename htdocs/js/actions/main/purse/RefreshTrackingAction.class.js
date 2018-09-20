NGS.createAction("crm.actions.main.purse.refresh_tracking", {
    onError: function (res) {
    },
    afterAction: function (res) {
        var html = "";
        if (res.tracking_url !== '')
        {
            html = '<a class="link" target="_black" href="' + res.tracking_url + '" >' + res.tracking_number + '</a>';
        } else {
            html = res.tracking_number;
        }
        $('#tracking_' + res.id).html(html);
    }
});
