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
        $("input[type='checkbox']").each(function () {
            if ($(this).is(":checked"))
            {
                $(this).parent('.f_checkbox').addClass('checked');
            } else
            {
                $(this).parent('.f_checkbox').removeClass('checked');

            }
        });
        $(".f_checkbox").on("click", function () {
            $(this).toggleClass("checked");
            var checkbox = $(this).find("input[type='checkbox']");
            checkbox.prop("checked", $(this).hasClass("checked"));
            checkbox.attr("checked", $(this).hasClass("checked"));
            checkbox.trigger('change');
        });
    }

});
