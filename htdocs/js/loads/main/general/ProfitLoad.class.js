NGS.createLoad("crm.loads.main.general.profit", {
    getContainer: function () {
        return "profitCalculationContainer";
    },
    onError: function (params) {

    },
    afterLoad: function () {
        $('#profitCalculationContainer select').not("[data-no-wrap=true]").wrap("<div class='select_wrapper' />");
        var thisInstance = this;
        $('#profitCalculationContainer select').change(function () {
            var params = thisInstance.getFromAndToDateParams();
            NGS.load('crm.loads.main.general.profit', params);
        });
        this.initChart();

    },
    initChart: function () {
        google.load("visualization", "1", {packages: ["corechart"]});
        google.setOnLoadCallback(function () {
            var data = google.visualization.arrayToDataTable([
                ['Task', 'Hours per Day'],
                ['Work', 11],
                ['Eat', 2],
                ['Commute', 2],
                ['Watch TV', 2],
                ['Sleep', 7]
            ]);

            var options = {
                title: 'My Daily Activities'
            };

            var chart = new google.visualization.PieChart(document.getElementById('piechart'));

            chart.draw(data, options);
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
