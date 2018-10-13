NGS.createAction("crm.actions.main.purse.set_problematic", {
    onError: function (res) {
    },
    afterAction: function (res) {
        if (res.problematic == 1) {
            $('#problematic_' + res.id).closest('tr').css('background', 'orange');
        } else
        {
            $('#problematic_' + res.id).closest('tr').css('background', '');

        }
    }
});
