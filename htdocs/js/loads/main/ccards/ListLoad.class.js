NGS.createLoad("crm.loads.main.ccards.list", {
    search_timout_handler: null,
    getContainer: function () {
        return "initialLoad";
    },
    onError: function (params) {

    },
    afterLoad: function () {
        $('.f_delete').click(function () {
            var row = $(this);
            Modals.showConfirmDlg('Confirm', 'are you sure you want to delete?', null, null, function () {
                $(row).closest('tr').remove();
                NGS.action('crm.actions.main.ccards.delete', {id: row.closest('tr').data('id')});
            });
        });
    }
});
