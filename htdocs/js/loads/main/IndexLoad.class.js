NGS.createLoad("crm.loads.main.index", {
    getContainer: function () {
        return "initialLoad";
    },
    onError: function (params) {

    },
    afterLoad: function () {
        this.wrapSelect();
        this.datetimepicker();
        this.datepicker();
        this.autocompleteSelect();
        this.checkbox();
        this.initCloseModal();
        this.initEditableCells();
    },
    initEditableCells: function () {
        $('.f_editable_cell').dblclick(function () {
            var cellValues = $(this).text().trim();
            var cellFieldName = $(this).data('field-name');
            var type = $(this).data('type');
            if (type === 'richtext') {
                var input = $('<textarea ondblclick="event.preventDefault();event.stopPropagation();" style="width:100%;height:100%" data-id="' + id + '" data-field-name="' + cellFieldName + '">' + cellValues.htmlEncode() + '</textarea>')
            } else {
                var input = $('<input ondblclick="event.preventDefault();event.stopPropagation();" style="width:100%;height:100%" data-id="' + id + '" data-field-name="' + cellFieldName + '" type="text" value="' + cellValues.htmlEncode() + '"/>')
            }

            var id = $(this).parent('div').data('id');
            $(this).html(input);
            var cellElement = $(this);
            input.focus();
            input.blur(function () {
                var id = $(this).data('id');
                var fielldName = $(this).data('field-name');
                var value = $(this).val().trim();
                cellElement.html(value);
                $(this).off();
                NGS.action('crm.actions.main.UpdateField',
                        {'id': id, 'object_type': 'product',
                            'field_name': fielldName,
                            "field_value": value},
                        function (ret) {
                            cellElement.html(ret.value);
                        });
            });
        });
    },
    initCloseModal: function () {
        $('.modal .modal-close').click(function () {
            $(this).closest('.modal').removeClass('is_active');
        });
    },
    datetimepicker: function () {
        $('.datetimepicker').datetimepicker({
            format: 'Y-m-d H:i',
            inline: true,
            lang: 'hy',
            step: 1
        });
    },
    datepicker: function () {
        $('.datepicker').datetimepicker({
            format: 'Y-m-d',
            inline: true,
            timepicker: false,
            lang: 'hy',
            step: 1
        });
    },
    autocompleteSelect: function () {
        $("select[data-autocomplete=true]").chosen({
            search_contains: true
        });
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
