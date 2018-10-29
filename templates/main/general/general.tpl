<div class="container general--container" >
    {literal} 
        <script type="text/javascript" src="https://www.google.com/jsapi?autoload={'modules':[{'name':'visualization','version':'1.1','packages':['corechart']}]}"></script>
    {/literal} 
    <h1 class="main_title">General</h1>
    <h1>Capital: {$ns.capital|number_format:2}<h1/>  
        partnerDebtTotal: {$partnerDebtTotal}, 
        warehouseTotal: {$warehouseTotal}, 
        purseTotal, {$purseTotal},
        purseBalanceTotal, {$purseBalanceTotal},
        partnerWarehouseTotal, {$partnerWarehouseTotal})<br/><br/><br/>
    <div id="cashboxCalculationContainer">
        {nest ns=cashboxCalculation}
    </div>
    <div id="profitCalculationContainer">
        {nest ns=profitCalculation}
    </div>
</div>