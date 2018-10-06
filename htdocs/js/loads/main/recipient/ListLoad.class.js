NGS.createLoad("crm.loads.main.recipient.list", {
    getContainer: function () {
        return "initialLoad";
    },
    onError: function (params) {

    },
    afterLoad: function () {
        $('#recipientFilters').find('input, select, checkbox').change(function () {
            $('#recipientFilters').trigger('submit');
        });
        $('.f_deleted_checkbox').change(function () {
            var recipient_id = $(this).data('recipient_id');
            var deleted = $(this).is(':checked') ? 1 : 0;

            NGS.action('crm.actions.main.recipient.set_recipient_hidden', {recipient_id: recipient_id, deleted: deleted});
        });
        $('.f_favorite_checkbox').change(function () {
            var recipient_id = $(this).data('recipient_id');
            var favorite = $(this).is(':checked') ? 1 : 0;

            NGS.action('crm.actions.main.recipient.set_recipient_favorite', {recipient_id: recipient_id, favorite: favorite});
        });
    }
});
