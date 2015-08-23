NGS.createLoad("crm.loads.main.main", {
    getContainer: function () {
        return "initialLoad";
    },
    onError: function (params) {

    },
    afterLoad: function () {
        this.wrapSelect();
        this.autocompleteSelect();
        this.checkbox();
    },
    autocompleteSelect: function () {
        $("select[data-autocomplete=true]").chosen();
    },
    wrapSelect: function () {
        $("select").not("[data-no-wrap=true]").not("[data-autocomplete=true]").wrap("<div class='select_wrapper' />");
    },
    checkbox: function () {
        $(".f_checkbox_label").on("click", function () {
            $(this).siblings(".f_checkbox").trigger("click");
        });
        $(".f_checkbox").on("click", function () {
            $(this).toggleClass("checked");
            var checkbox = $(this).find("input[type='checkbox']");
            if ($(this).hasClass("checked")) {
                checkbox.prop("checked", true);
            }
            else {
                checkbox.prop("checked", false);
            }
        });
    }

});
