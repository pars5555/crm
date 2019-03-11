NGS.createAction("crm.actions.main.UpdateField", {
    onError: function (res) {
    },
    afterAction: function (params) {
        if (params.success === false){
            alert(params.message);
        }
    }
});
