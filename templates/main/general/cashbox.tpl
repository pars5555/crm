
<div class="cashboxContainer">
    <div class="section-title">Amount</div>
    <div class="date-container calendar-container">
        <label class="label">Date </label>
        <div class="form-group">
            <input id="cashboxDate" name ='date' type="text" value="{$ns.date}"/>
        </div>
    </div>
    <div class="currency-container calendar-container">
        <label class="label">Currency</label>
        <select id="cashboxCurrencySelect">
            {foreach from=$ns.currencies item=c}
                <option {if $c->getId() == $ns.selectedCurrencyId}selected{/if}
                        value="{$c->getId()}">{$c->getName()} ({$c->getIso()} {$c->getTemplateChar()})</option>
            {/foreach}
        </select>
    </div>
</div>
Amount: {$ns.amount}