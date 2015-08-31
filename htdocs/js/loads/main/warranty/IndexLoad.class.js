NGS.createLoad("crm.loads.main.warranty.index", {
    getContainer: function () {
        return "initialLoad";
    },
    onError: function (params) {

    },
    afterLoad: function () {
        $('#searchButton').click(function () {
            var search = $('#searchSerialNumber').val();
            NGS.load('crm.loads.main.warranty.content', {search: search});
        });
    }
});
