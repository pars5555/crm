Profit: 
<div class="form-group">    
    <label class="label">From </label>
    {html_select_date prefix='startDate' start_year=2010 end_year=2020 field_order=YMD time=$ns.startDate}
    <label class="label">To </label>
    {html_select_date prefix='endDate' start_year=2010 end_year=2020 field_order=YMD time=$ns.endDate}
    <div>
        Profit: {$ns.profit}
    </div>
    <div id="piechart" style="width: 900px; height: 500px;"></div>
</div>
