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

            NGS.action('crm.actions.main.recipient.set_recipient_deleted', {recipient_id: recipient_id, deleted: deleted});
        });
        $('.f_checked_checkbox').change(function () {
            var recipient_id = $(this).data('recipient_id');
            var checked = $(this).is(':checked') ? 1 : 0;
            NGS.action('crm.actions.main.recipient.set_recipient_checked', {recipient_id: recipient_id, checked: checked});
        });
        $('.f_favorite_checkbox').change(function () {
            var recipient_id = $(this).data('recipient_id');
            var favorite = $(this).is(':checked') ? 1 : 0;

            NGS.action('crm.actions.main.recipient.set_recipient_favorite', {recipient_id: recipient_id, favorite: favorite});
        });
        this.initTooltip();
    },
    initTooltip: function () {
        $(document).tooltip({
            items: "[data-orders]",
            track: true,
            content: function () {
                var element = $(this);
                var data = element.data('orders');
                var titleHtml = "";
                $(data['orders']).each(function (index, order) {
                    titleHtml += '<img width="30" src="' + order['image_url'] + '"/>' + '<span>$' +
                            order['order_total'] + ' ' + 
                            order['status'] + ' ' + 
                            order['created_at'] + ' ' + '</span>' + "<br/><br/>";
                }); 
                return titleHtml;
            }
        });
    }
});
