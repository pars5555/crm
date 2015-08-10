<div class="container general--container">
   <h1>General</h1>
    <form id="GeneralForm" autocomplete="off" method="GET" action="{SITE_PATH}/general">
        <div class="form-group">
            <label class="label">Currency</label>
            <select name="cur">

                {foreach from=$ns.currencies item=c}
                    <option {if $c->getId() == $ns.selectedCurrencyId}selected{/if}
                                                                      value="{$c->getId()}">{$c->getName()} ({$c->getIso()} {$c->getTemplateChar()})</option>
                {/foreach}
            </select>
        </div>
    </form>
    Amount: {$ns.amount}
</div>
<div id="profitCalculationContainer">
    {nest ns=profitCalculation}
</div>