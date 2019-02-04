NGS.stickyNoteTimeoutInstance = 0;
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
        this.initSelectableCells();
        this.initSettingEditableCells();
        this.initLeftMenuTrigger();
        this.hideLeftMenuOnMobile();
        this.initFileUploader();
        this.initStickyNote();
    },
    initStickyNote: function () {
        var position = false;
        if ($.cookie("stickynote_position")) {
            var position = JSON.parse($.cookie("stickynote_position"));
        }
        $('#sticky_note_content').on('change copy paste keyup', function () {
            var page_name = $(this).data('page_name');
            if (NGS.stickyNoteTimeoutInstance) {
                window.clearTimeout(NGS.stickyNoteTimeoutInstance);
            }
            NGS.stickyNoteTimeoutInstance = window.setTimeout(function () {
                NGS.action('crm.actions.main.set_sticky_note',
                        {
                            page_name: page_name,
                            note: $('#sticky_note_content').val()
                        }, function (ret) {
                    $('#sticky_note_content').val(ret.note);
                });


            }, 500);

        });
        $("#sticky_note").dialog({
            autoOpen: $.cookie("stickynote_position") == 'true',
            drag: function (event, ui) {
                $.cookie("stickynote_position", JSON.stringify([$("#sticky_note").parent().position().left, $("#sticky_note").parent().position().top]));
                return true;
            },
            open: function () {
                if (position) {
                    $("#sticky_note").parent().css({'left': position[0], 'top': position[1]});
                }
            },
            close: function () {
                $.cookie("stickynote_autopen", 'false');
            }
        });
        $("#open_sticky_note").click(function () {
            $("#sticky_note").dialog('open');
            var position = JSON.parse($.cookie("stickynote_position"));
            $("#sticky_note").parent().css({'left': position[0], 'top': position[1]});
            $.cookie("stickynote_autopen", 'true');
        });
    },
    initFileUploader: function () {
        $('#select_attachment_button').click(function () {
            $('#file_input').trigger('click');
        });
        $('#file_input').change(function () {
            $('#upload_attachment_form').trigger('submit');

        });
        $('.f_remove_attachment').click(function () {

            NGS.action('crm.actions.attachment.delete_attachment',
                    {'id': $(this).data('id')});
        });

    },
    initSelectableCells: function () {
        $("body").on("dblclick", ".f_selectable_cell", function () {
            var templateSelectId = $(this).data('template-select-id');
            var selectElement = $('#' + templateSelectId).clone();
            var value = $(this).data('value');
            var displayValue = $(this).text();
            var cellFieldName = $(this).data('field-name');
            var object_type = $(this).parent('tr').data('type');
            var id = $(this).closest('tr').data('id');
            if (value >= 0 || value.length > 0) {
                $(selectElement).val(value);
            }
            selectElement.removeAttr('id');
            selectElement.removeClass('hidden');
            $(this).html(selectElement);
            var cellElement = $(this);
            selectElement.focus();
            selectElement.blur(function () {
                selectElement.remove();
                cellElement.html(displayValue);
            });
            selectElement.change(function () {
                var value = $(this).val();
                cellElement.data('value', value);
                $(this).off();
                NGS.action('crm.actions.main.UpdateField',
                        {'id': id,
                            'object_type': object_type,
                            'field_name': cellFieldName,
                            "field_value": value}, function (ret) {
                    cellElement.data('value', ret.value);
                    selectElement.remove();
                    cellElement.html(ret.display_value);
                });
            });
        });
    },
    initEditableCells: function () {
        $(document).on('dblclick', '.f_editable_cell', function () {
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
        $(document).on('dblclick', '.f_editable_setting_field', function () {
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
        $(document).on('click', '.modal .modal-close', function () {
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
function attachmentUploadTarget() {
    window.location.reload(true);
}
