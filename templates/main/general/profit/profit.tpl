Profit: 
<div class="form-group">    
    <label class="label">From </label>
    <input class="datepicker" name ='startDate' id='startDateWidget' type="text" value="{$ns.startDate}"/>
    <label class="label">To </label>
    <input class="datepicker" name ='endDate' id='endDateWidget' type="text" value="{$ns.endDate}"/>
    <div>
        Profit: {$ns.profit}
    </div>
    <div id="piechart" style="width: 800px; height: 400px;"></div>
    <div id="curve_chart" style="width: 800px; height: 400px;"></div>

    <div id="chartData" class="hidden">{$ns.chartData}</div>
    <div id="lineChartData" class="hidden">{$ns.lineChartData}</div>
    <input type="hidden" id="startDate" value="{$ns.startDate}"/>
    <input type="hidden" id="endDate" value="{$ns.endDate}"/>

    <div id="chartSelectionContainer">
    </div>
</div>
