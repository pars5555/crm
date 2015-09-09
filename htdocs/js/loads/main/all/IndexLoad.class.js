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
