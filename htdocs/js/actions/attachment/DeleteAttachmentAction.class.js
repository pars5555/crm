NGS.createAction("crm.actions.attachment.delete_attachment", {
    onError: function (res) {
    },
    afterAction: function (params) {
        window.location.reload(true);
    }
});
