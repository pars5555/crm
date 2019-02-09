NGS.createLoad("crm.loads.main.general.profit", {
    getContainer: function () {
        return "profitCalculationContainer";
    },
    onError: function (params) {

    },
    afterLoad: function () {
        $('#profitCalculationContainer select').not("[data-no-wrap=true]").wrap("<div class='select_wrapper' />");
        this.initFromAndToDateParams();
        this.initChart();
        this.initChart2();

    },
    initChart2: function () {
        var chartData = jQuery.parseJSON($('#lineChartData').text());
        var chartDataFormatted = [];
        chartDataFormatted.push(['Date', 'Sales Profit', 'Sale Expenses', 'Payment Expenses', 'Sales Amout']);
        $.each(chartData, function (key, value) {
            chartDataFormatted.push([new Date(key), value[0], value[1], value[2], value[3]]);
        });
        var kyz = Object.keys(chartData);
        var data = google.visualization.arrayToDataTable(chartDataFormatted);

        var options = {
            title: 'Company Performance',
            curveType: 'function',
            // legend: {position: 'bottom'},
            width: 800,
            height: 400,
            hAxis: {
                format: 'yyyy-MM-dd',
                title: 'Year End',
                ticks: [
                    new Date(kyz[0]),
                    new Date(kyz[Math.round(kyz.length / 3)]),
                    new Date(kyz[Math.round(kyz.length / 1.5)]),
                    new Date(kyz[kyz.length - 1])
                ]
            }
        };
        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
        chart.draw(data, options);
    },
    initChart: function () {
        var chartData = jQuery.parseJSON($('#chartData').text());
        var data = google.visualization.arrayToDataTable([
            ['Task', 'Profit Chart'],
            ['Profit Without Expenses', chartData.profit_without_expenses],
            ['Payment Expenses', chartData.payment_expenses],
            ['Sale Expenses', chartData.sale_expenses]
        ]);
        var options = {
            title: 'Expenses',
            width: 800,
            height: 400,
            is3D: true,
            sliceVisibilityThreshold: 0
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));
        google.visualization.events.addListener(chart, 'select', function (event) {
            var selection = chart.getSelection();
            if (selection.length === 0)
            {
                return true;
            }
            var selectedPieIndex = parseInt(selection[0].row);
            switch (selectedPieIndex) {
                case 0:
                    //'Profit Without Expenses'
                    NGS.load('crm.loads.main.general.profit_chart_selection_one_data', {startDate: $('#startDate').val(), endDate: $('#endDate').val()});
                    break;
                case 1:
                    //'Payment Expenses'
                    NGS.load('crm.loads.main.general.profit_chart_selection_two_data', {startDate: $('#startDate').val(), endDate: $('#endDate').val()});
                    break;
                case 2:
                    //'Sale Expenses'
                    NGS.load('crm.loads.main.general.profit_chart_selection_three_data', {startDate: $('#startDate').val(), endDate: $('#endDate').val()});
                    break;
            }
        });
        chart.draw(data, options);

    },
    initFromAndToDateParams: function () {

        $('#startDateWidget').datetimepicker({
            format: 'Y-m-d',
            inline: true,
            timepicker: false,
            step: 1,
            lang:'hy',
            onSelectDate: function (ct, $i) {
                $('#startDate').val(ct.dateFormat('Y-m-d'));
                NGS.load('crm.loads.main.general.profit', {startDate: $('#startDate').val(), endDate: $('#endDate').val()});
            }
        });
        $('#endDateWidget').datetimepicker({
            format: 'Y-m-d',
            inline: true,
            timepicker: false,
            step: 1,
            lang:'hy',
            onSelectDate: function (ct, $i) {
                $('#endDate').val(ct.dateFormat('Y-m-d'));
                NGS.load('crm.loads.main.general.profit', {startDate: $('#startDate').val(), endDate: $('#endDate').val()});
            }
        });
    }

});
