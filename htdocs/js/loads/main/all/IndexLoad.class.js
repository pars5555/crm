NGS.createLoad("crm.loads.main.all.index", {
    getContainer: function () {
        return "indexRightContent";
    },
    onError: function (params) {

    },
    afterLoad: function () {
        $('.all--list--container select').not("[data-no-wrap=true]").wrap("<div class='select_wrapper' />");
        var thisInstance = this;
        $('.all--list--container select').change(function () {
            var params = thisInstance.getFromAndToDateParams();
            NGS.load('crm.loads.main.all.index', params);
        });
        this.initEditableCells();
        this.initChecked();
        this.initCheckAllCheckbox();
    },
    initCheckAllCheckbox: function () {
        $('#f_check_all_checkbox').change(function () {
            var destClass = $(this).data('destination_class');
            var count = $('.' + destClass).length;
            if (count > 50) {
                if (window.confirm('It will change ' + count + ' checkbox selection, are you sure?'))
                {
                    $('.' + destClass).prop("checked", $(this).is(":checked"));
                    $('.' + destClass).trigger('change');
                }
            } else
            {
                $('.' + destClass).prop("checked", $(this).is(":checked"));
                $('.' + destClass).trigger('change');
            }
        });
    },
    initChecked: function () {
        $('.f_checked_checkbox').change(function () {
            var id = $(this).data('id');
            var checked = $(this).is(':checked') ? 1 : 0;
            var object_type = $(this).data('type');
            NGS.action('crm.actions.main.set_object_checked', {object_type: object_type, id: id, checked: checked});
        });
    },
    initEditableCells: function () {
        $('.f_editable_cell').dblclick(function () {
            var cellValues = $(this).text().trim();
            var cellFieldName = $(this).data('field-name');
            var id = $(this).parent('div').data('id');
            var object_type = $(this).parent('div').data('type');
            if (typeof object_type === 'undefined'){
                object_type = $(this).parent('tr').data('type');
                id = $(this).parent('tr').data('id');
                
            }
            var input = $('<input ondblclick="event.preventDefault();event.stopPropagation();" style="width:100%;height:100%" data-id="' + id + '" data-field-name="' + cellFieldName + '" type="text" value="' + cellValues.htmlEncode() + '"/>')
            $(this).html(input);
            var cellElement = $(this);
            input.focus();
            input.blur(function () {
                var id = $(this).data('id');
                var fielldName = $(this).data('field-name');
                var value = $(this).val().trim();
                cellElement.html(value);
                $(this).off();
                NGS.action('crm.actions.main.UpdateField', {'id': id, 'object_type': object_type, 'field_name': fielldName, "field_value": value}, function (ret) {
                    cellElement.html(ret.value);
                });
            });
        });
    },
    getFromAndToDateParams: function () {
        var startYear = $("select[name='startDateYear']").val();
        var startMonth = $("select[name='startDateMonth']").val();
        var startDay = $("select[name='startDateDay']").val();
        var endYear = $("select[name='endDateYear']").val();
        var endMonth = $("select[name='endDateMonth']").val();
        var endDay = $("select[name='endDateDay']").val();
        return {startDateYear: startYear, startDateMonth: startMonth, startDateDay: startDay,
            endDateYear: endYear, endDateMonth: endMonth, endDateDay: endDay};
    }
});
