Profit: 
<div class="form-group">
    <label class="label">From </label>
    {html_select_date prefix='paymentDate' start_year=2010 end_year=2020 field_order=YMD time=$ns.fromDate}
    <label class="label">To </label>
    {html_select_date prefix='paymentDate' start_year=2010 end_year=2020 field_order=YMD time=$ns.toDate}
    <div>
        Profit: {$ns.profit}
    </div>
</div>
