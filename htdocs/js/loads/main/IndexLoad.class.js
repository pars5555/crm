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
        this.initSettingEditableCells();
        this.initLeftMenuTrigger();
        this.hideLeftMenuOnMobile();
    },
    initEditableCells: function () {
        $('.f_editable_cell').dblclick(function () {
            var cellValues = $(this).text().trim();
            var cellFieldName = $(this).data('field-name');
            var type = $(this).data('type');
            var object_type = $(this).parent('div').data('type');
            var id = $(this).parent('div').data('id');
            if (typeof object_type === 'undefined') {
                object_type = $(this).parent('tr').data('type');
                id = $(this).parent('tr').data('id');

            }
            if (type === 'richtext') {
                var input = $('<textarea ondblclick="event.preventDefault();event.stopPropagation();" style="width:100%;height:100%;min-width:150px;min-height:50px" data-id="' + id + '" data-field-name="' + cellFieldName + '">' + cellValues.htmlEncode() + '</textarea>')
            } else {
                var input = $('<input ondblclick="event.preventDefault();event.stopPropagation();" style="width:100%;height:100%" data-id="' + id + '" data-field-name="' + cellFieldName + '" type="text" value="' + cellValues.htmlEncode() + '"/>')
            }

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
                        {'id': id, 'object_type': object_type,
                            'field_name': fielldName,
                            "field_value": value},
                        function (ret) {
                            cellElement.html(ret.value);
                        });
            });
        });
    },
    initSettingEditableCells: function () {
        $('.f_editable_setting_field').dblclick(function () {
            var cellValues = $(this).text().trim();
            var cellFieldName = $(this).data('field-name');
            var type = $(this).data('type');
            if (type === 'richtext') {
                var input = $('<textarea ondblclick="event.preventDefault();event.stopPropagation();" style="width:100%;height:100%;min-width:150px;min-height:50px"  data-field-name="' + cellFieldName + '">' + cellValues.htmlEncode() + '</textarea>')
            } else {
                var input = $('<input ondblclick="event.preventDefault();event.stopPropagation();" style="width:100%;height:100%" data-field-name="' + cellFieldName + '" type="text" value="' + cellValues.htmlEncode() + '"/>')
            }
            $(this).html(input);
            var cellElement = $(this);
            input.focus();
            input.blur(function () {
                var fielldName = $(this).data('field-name');
                var value = $(this).val().trim();
                cellElement.html(value);
                $(this).off();
                NGS.action('crm.actions.main.UpdateField',
                        {'object_type': 'settings_name',
                            'field_name': fielldName,
                            "field_value": value},
                        function (ret) {
                            cellElement.html(ret.value);
                        });
            });
        });
    },
    initCloseModal: function () {
        $(document).on('click','.modal .modal-close',function () {
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
    },
    initLeftMenuTrigger: function () {
        $("#leftMenuTrigger").on('click', function () {
            $(this).toggleClass('menu_hidden');
            $("#mainWrapper").toggleClass('menu_hidden');
        })
    },
    hideLeftMenuOnMobile: function () {
        if (window.outerWidth <= 768) {
            $("#leftMenuTrigger").click();
        }
    }
});
