NGS.createAction("crm.actions.main.purse.set_hidden_and_create_purchase", {
    onError: function (res) {
    },
    afterAction: function (res) {
        console.log(res);
        $('#hide_modalBox').removeClass('is_active');
        $('[data-id="' + res.id + '"]').remove();
    }
});
