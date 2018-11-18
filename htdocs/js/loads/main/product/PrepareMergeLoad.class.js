NGS.createLoad("crm.loads.main.product.prepare_merge", {
    getContainer: function () {
        return "prepare_merge_modalBox";
    },
    onError: function (params) {

    },
    afterLoad: function () {
        $('#prepare_merge_modalBox').addClass('is_active');
        $("#dst_product_id").chosen({
            search_contains: true
        });
    }
});
