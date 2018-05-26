<div class="container all--list--container">
    <h1 class="main_title">All Deals</h1>
    <div class="form-group">    
        <label class="label">From </label>
        {html_select_date prefix='startDate' start_year=2010 end_year=2020 field_order=YMD time=$ns.startDate}
        <label class="label">To </label>
        {html_select_date prefix='endDate' start_year=2010 end_year=2020 field_order=YMD time=$ns.endDate}
    </div>

    <div class="table_striped">
        <div class="table_header_group">
            <span class="table-cell"> ID </span>
            <span class="table-cell"> Type </span>
            <span class="table-cell"> Partner </span>
            <span class="table-cell"> Date </span>
            <span class="table-cell"> Note </span>
            <span class="table-cell"> Amount </span>            
        </div> 
        {foreach from=$ns.allDeals item=deal}
            <div class="table-row" data-type="{$deal[0]}" data-id="{$deal[1]->getId()}" {if $deal[1]->getChecked() == 1}style="background: green"{/if}>

                <a class="table-cell"  href="{$SITE_PATH}/{$deal[0]}/{$deal[1]->getId()}" target="_blank">
                    <span>{$deal[1]->getId()} </span>
                </a>
                <a class="table-cell" href="{$SITE_PATH}/{$deal[0]}/{$deal[1]->getId()}" target="_blank">
                    <span>{$deal[0]} </span>
                </a>
                <a class="table-cell" href="{$SITE_PATH}/partner/{$deal[1]->getPartnerDto()->getId()}" target="_blank">
                    <span>{$deal[1]->getPartnerDto()->getName()} </span>
                </a>
                <a class="table-cell">
                    {if $deal[0] == 'sale' || $deal[0] == 'purchase'}
                        <span>{$deal[1]->getOrderDate()} </span>
                    {else}
                        <span>{$deal[1]->getDate()} </span>
                    {/if}
                </a>
                <a class="table-cell f_editable_cell" data-field-name="note">
                    <span>{$deal[1]->getNote()} </span>
                </a>
                <span class="table-cell "> <input data-type="{$deal[0]}" data-id="{$deal[1]->getId()}" class="f_checked_checkbox"  type="checkbox" value="1" {if $deal[1]->getChecked() ==1}checked{/if}/></span>
                <span class="table-cell">
                    {if $deal[0] == 'sale' || $deal[0] == 'purchase'}
                        {assign totalAmount $deal[1]->getTotalAmount()}
                        {foreach from=$totalAmount key=currencyId item=amount}
                            <span class="price">
                                {assign currencyDto $ns.currencies[$currencyId]}
                                {if $currencyDto->getSymbolPosition() == 'left'}
                                    {$currencyDto->getTemplateChar()}
                                {/if}
                                {$amount|number_format:2}
                                {if $currencyDto->getSymbolPosition() == 'right'}
                                    {$currencyDto->getTemplateChar()}
                                {/if}
                            </span>
                        {/foreach}

                    {else}
                        <span class="price">
                            {assign currencyDto $ns.currencies[$deal[1]->getCurrencyId()]}
                            {if $currencyDto->getSymbolPosition() == 'left'}
                                {$currencyDto->getTemplateChar()}
                            {/if}
                            {if $deal[0] == 'billing'}
                                {(-$deal[1]->getAmount())|number_format:2}
                            {else}
                                {$deal[1]->getAmount()|number_format:2}
                            {/if}
                            {if $currencyDto->getSymbolPosition() == 'right'}
                                {$currencyDto->getTemplateChar()}
                            {/if}
                        </span>
                        <span> </span>
                    {/if}
                </span>

            </div>
        {/foreach}
    </div>


</div>