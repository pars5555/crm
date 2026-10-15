<div class="container general--container" >
    {literal}
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    {/literal}
    <h1 class="main_title">General</h1>

    {* The same figures as before, shown as cards. The original technical names stay as tooltips. *}
    <div class="kpi-grid">
        <div class="kpi kpi-primary" title="capital">
            <div class="kpi-label">Capital</div>
            <div class="kpi-value">{$ns.capital|number_format:2}</div>
        </div>
        <div class="kpi kpi-warn" title="partnerDebtTotal">
            <div class="kpi-label">Partner debts</div>
            <div class="kpi-value">{$partnerDebtTotal|number_format:2}</div>
        </div>
        <div class="kpi kpi-ok" title="warehouseTotal">
            <div class="kpi-label">Warehouse stock</div>
            <div class="kpi-value">{$warehouseTotal|number_format:2}</div>
        </div>
        <div class="kpi" title="btcProductsTotal">
            <div class="kpi-label">BTC products</div>
            <div class="kpi-value">{$purseTotal|number_format:2}</div>
        </div>
        <div class="kpi" title="btcBalancesTotal">
            <div class="kpi-label">BTC balances</div>
            <div class="kpi-value">{$purseBalanceTotal|number_format:2}</div>
        </div>
        <div class="kpi" title="cashboxTotal">
            <div class="kpi-label">Cash boxes</div>
            <div class="kpi-value">{$cashboxTotal|number_format:2}</div>
        </div>
        <div class="kpi" title="capital_external_btc">
            <div class="kpi-label">External BTC</div>
            <div class="kpi-value">{$capital_external_btc|number_format:2}</div>
        </div>
        <div class="kpi" title="capital_external_debts">
            <div class="kpi-label">External debts</div>
            <div class="kpi-value">{$capital_external_debts|number_format:2}</div>
        </div>
    </div>

    <div class="panel">
        <div class="panel-title">Manual adjustments <small style="font-weight:400;color:#6a7686">&mdash; click a value to edit it</small></div>
        <div class="row">
            <span>External debts $</span>
            <span class="f_editable_setting_field" data-field-name="capital_external_debts">{$ns.capital_external_debts|number_format:2:".":""}</span>
        </div>
        <div class="row">
            <span>Note</span>
            <span class="f_editable_setting_field" data-field-name="capital_external_debts_note">{$ns.capital_external_debts_note|default:"External Debt Note"}</span>
        </div>
        <div class="row">
            <span>Bitpay btc $</span>
            <span class="f_editable_setting_field" data-field-name="capital_external_btc">{$ns.capital_external_btc|number_format:2:".":""}</span>
        </div>
    </div>

    <div id="cashboxCalculationContainer" class="panel">
        {nest ns=cashboxCalculation}
    </div>
    <div id="profitCalculationContainer" class="panel">
        {nest ns=profitCalculation}
    </div>
</div>
