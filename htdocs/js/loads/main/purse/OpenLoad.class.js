NGS.createLoad("crm.loads.main.purse.open", {
    getContainer: function () {
        return "initialLoad";
    },
    onError: function (params) {

    },
    afterLoad: function () {
        $('.deleteProductButton').click(function () {
            if (confirm("Are you sure you want to delete product?"))
            {
                return true;
            }
            return false;
        });
    }
});
