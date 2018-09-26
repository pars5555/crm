<div class="form-group profit-calendars">
    <div class="section-title">Profit</div>
    <div class="date-from calendar-container">
        <label class="label">From </label>
        <input   id='startDateWidget' type="text" value="{$ns.startDate}"/>
    </div>
    <div class="date-to calendar-container">
        <label class="label">To </label>
        <input id='endDateWidget' type="text" value="{$ns.endDate}"/>
    </div>
    <div>
        Profit: {$ns.profit}
    </div>

    <div class="chart-container">
        <div id="piechart"></div>
    </div>
    <div class="chart-container">
        <div id="curve_chart"></div>
    </div>

    <div id="chartData" class="hidden">{$ns.chartData}</div>
    <div id="lineChartData" class="hidden">{$ns.lineChartData}</div>
    <input type="hidden" id="startDate" value="{$ns.startDate}"/>
    <input type="hidden" id="endDate" value="{$ns.endDate}"/>

    <div id="chartSelectionContainer">
    </div>
</div>
