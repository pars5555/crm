NGS.createAction("crm.actions.main.product.reserve_product", {
    onError: function (res) {
        alert(res.msg);
    },
    afterAction: function (data) {
        if (data.success == true) {
            $('#add_reservation_modalBox').removeClass('is_active');
        } else {
            $('#add_reservation_modalBox .f_error_message').text(data.message);
            
        }

    }
});
