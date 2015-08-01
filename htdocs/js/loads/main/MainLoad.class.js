NGS.createLoad("crm.loads.main.main", {
    getContainer: function () {
        return "initialLoad";
    },
    onError: function (params) {

    },
    afterLoad: function () {
        this.wrapSelect();
    },
    wrapSelect: function () {
        $("select").not("[data-no-wrap=true]").wrap("<div class='select_wrapper' />");
    }
});
