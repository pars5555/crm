<div class="container general--container" >
    {literal} 
        <script type="text/javascript" src="https://www.google.com/jsapi?autoload={'modules':[{'name':'visualization','version':'1.1','packages':['corechart']}]}"></script>
    {/literal} 
    <h1 class="main_title">General</h1>
    <h1>Capital: {$ns.capital|number_format:2}</h1>  
    partnerDebtTotal: {$partnerDebtTotal|number_format:2}, 
    warehouseTotal: {$warehouseTotal|number_format:2}, 
    btcProductsTotal, {$purseTotal|number_format:2},
    btcBalancesTotal, {$purseBalanceTotal|number_format:2},
    cashboxTotal, {$cashboxTotal|number_format:2},
    capital_external_btc, {$capital_external_btc|number_format:2},
    capital_external_debts, {$capital_external_debts|number_format:2}<br/><br/><br/>

    <div>
        external debts $: <span class="f_editable_setting_field" data-field-name="capital_external_debts">{$ns.capital_external_debts|number_format:2:".":""}</span>
    </div>
    <div>
        note: <span class="f_editable_setting_field" data-field-name="capital_external_debts_note">{$ns.capital_external_debts_note|default:"External Debt Note"}</span>
    </div>
    <div>
        Bitpay btc $: <span class="f_editable_setting_field" data-field-name="capital_external_btc">{$ns.capital_external_btc|number_format:2:".":""}</span>
    </div>

    <div id="cashboxCalculationContainer">
        {nest ns=cashboxCalculation}
    </div>
    <div id="profitCalculationContainer">
        {nest ns=profitCalculation}
    </div>
</div>