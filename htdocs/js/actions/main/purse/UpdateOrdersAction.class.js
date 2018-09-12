NGS.createAction("crm.actions.main.purse.update_orders", {
    onError: function (res) {
        alert('Update Token');
    },
    afterAction: function (res) {
        if (res.success == false) {
            alert('Update token');
        }else{
            window.location.reload();
        }
        
        
    }
});
