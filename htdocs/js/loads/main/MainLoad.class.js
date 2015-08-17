NGS.createLoad("crm.loads.main.main", {
    getContainer: function () {
        return "initialLoad";
    },
    onError: function (params) {

    },
    afterLoad: function () {
        this.wrapSelect();
        this.checkbox();
    },
    wrapSelect: function () {
        $("select").not("[data-no-wrap=true]").wrap("<div class='select_wrapper' />");
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
