NGS.createLoad("crm.loads.main.giftcards.open", {
    getContainer: function () {
        return "initialLoad";
    },
    onError: function (params) {

    },
    afterLoad: function () {
        $('.deleteAttachment').click(function () {
            if (confirm("Are you sure you want to delete product?"))
            {
                return true;
            }
            return false;
        });
    }
});
