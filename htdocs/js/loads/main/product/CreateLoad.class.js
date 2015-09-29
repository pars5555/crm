NGS.createLoad("crm.loads.main.product.create", {
    getContainer: function () {
        return "initialLoad";
    },
    onError: function (params) {

    },
    afterLoad: function () {
        var thisInstance = this;
        $('#productName').on('input', function () {
            if (thisInstance.timer) {
                window.clearTimeout(thisInstance.timer);
            }
            var searchText = $(this).val();
            thisInstance.timer = window.setTimeout(function () {
                NGS.load('crm.loads.main.product.search_list', {'searchText': searchText});
            }, 1000);
        });
        $('#productName').on('blur', function () {
            if (thisInstance.timer) {
                window.clearTimeout(thisInstance.timer);
            }
            NGS.load('crm.loads.main.product.search_list', {'searchText': $(this).val()});
        });



    }
});
