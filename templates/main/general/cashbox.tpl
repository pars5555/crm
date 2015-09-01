
<div class="cashboxContainer">
    <label class="label">Currency</label>
    <select id="cashboxCurrencySelect">

        {foreach from=$ns.currencies item=c}
            <option {if $c->getId() == $ns.selectedCurrencyId}selected{/if}
                                                              value="{$c->getId()}">{$c->getName()} ({$c->getIso()} {$c->getTemplateChar()})</option>
        {/foreach}
    </select>
    <label class="label">Date </label>
    {html_select_date prefix='date' start_year=2010 end_year=2020 field_order=YMD time=$ns.date}
</div>
Amount: {$ns.amount}