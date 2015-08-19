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
        $("input[type='checkbox']").each(function(){
            if ($(this).is(":checked"))
            {
                $(this).parent('.f_checkbox').addClass('checked');
            }else
            {
                $(this).parent('.f_checkbox').removeClass('checked');
                
            }
        });
        $(".f_checkbox_label").on("click", function () {
            $(this).siblings(".f_checkbox").trigger("click");
        });
        $(".f_checkbox").on("click", function () {
            $(this).toggleClass("checked");
            var checkbox = $(this).find("input[type='checkbox']");
            checkbox.prop("checked", $(this).hasClass("checked"));
            checkbox.trigger('change');
        });
    }

});
