
<div class="cashboxContainer">
    <label class="label">Currency</label>
    <select id="cashboxCurrencySelect">

        {foreach from=$ns.currencies item=c}
            <option {if $c->getId() == $ns.selectedCurrencyId}selected{/if}
                                                              value="{$c->getId()}">{$c->getName()} ({$c->getIso()} {$c->getTemplateChar()})</option>
        {/foreach}
    </select>
    <label class="label">Date </label>
    <div class="form-group">
        <label class="label">Date</label>
        <input id="cashboxDate" name ='date' type="text" value="{$ns.date}"/>
    </div>
</div>
Amount: {$ns.amount}