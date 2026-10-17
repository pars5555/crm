<div id="mainLeftPanel"  class="left--panel">
    <div class="left--panel--content">
        <h1 class="left--panel--title">
            <span>
                {$ns.lm->getPhrase('Pages')|default:'Pages'}
            </span>
        </h1>
        <ul>
            {if $ns.userType == $ns.userTypeAdmin}
                {if !empty($ns.user) && $ns.user->getType() == 'root'}
                    <li><a {if $ns.loadName=='general' || $ns.loadName=='default'}class="active"{/if} href="{$SITE_PATH}/general">{$ns.lm->getPhrase('General')|default:'General'}</a></li>
                    <li><a {if $ns.loadName=='preorder'}class="active"{/if} href="{$SITE_PATH}/preorder/list?srt=order_date&ascdesc=DESC">{$ns.lm->getPhrase('Preorders')|default:'Preorders'}</a></li>
                    <li><a {if $ns.loadName=='whishlist'}class="active"{/if} href="{$SITE_PATH}/whishlist/list?srt=order_date&ascdesc=DESC">{$ns.lm->getPhrase('Whishlist')|default:'Whishlist'}</a></li>
                    <li><a {if $ns.loadName=='partner'}class="active"{/if}  href="{$SITE_PATH}/partner/list">{$ns.lm->getPhrase('Partners')|default:'Partners'}</a></li>
                    <li><a {if $ns.loadName=='sale'}class="active"{/if} href="{$SITE_PATH}/sale/list?srt=order_date&ascdesc=DESC">{$ns.lm->getPhrase('Sale Orders')|default:'Sale Orders'}</a></li>
                    <li><a {if $ns.loadName=='purchase'}class="active"{/if} href="{$SITE_PATH}/purchase/list?srt=order_date&ascdesc=DESC">{$ns.lm->getPhrase('Purchase Orders')|default:'Purchase Orders'}</a></li>
                    <li><a {if $ns.loadName=='payment'}class="active"{/if} href="{$SITE_PATH}/payment/list?srt=date&ascdesc=DESC">{$ns.lm->getPhrase('Payment Order')|default:'Payment Order'}</a></li>
                    <li><a {if $ns.loadName=='billing'}class="active"{/if} href="{$SITE_PATH}/billing/list?srt=date&ascdesc=DESC">{$ns.lm->getPhrase('Billing Order')|default:'Billing Order'}</a></li>
                    <li><a {if $ns.loadName=='all'}class="active"{/if} href="{$SITE_PATH}/all">{$ns.lm->getPhrase('All Deals')|default:'All Deals'}</a></li>
                    <li><a {if $ns.loadName=='warehouse'}class="active"{/if} href="{$SITE_PATH}/warehouse">{$ns.lm->getPhrase('Warehouse')|default:'Warehouse'}</a></li>
                    {* hidden: needs the warehouse_moves table, which was never created (the page crashed) <li><a {if $ns.loadName=='rwarehouses'}class="active"{/if} href="{$SITE_PATH}/rwarehouses">Real Warehouses</a></li> *}
                    <li><a {if $ns.loadName=='pwarehouse'}class="active"{/if} href="{$SITE_PATH}/pwarehouse">{$ns.lm->getPhrase('Partner Warehouse')|default:'Partner Warehouse'}</a></li>
                    <li><a {if $ns.loadName=='product'}class="active"{/if} href="{$SITE_PATH}/product/list">{$ns.lm->getPhrase('Products')|default:'Products'}</a></li>
                    <li><a {if $ns.loadName=='manufacturer'}class="active"{/if} href="{$SITE_PATH}/manufacturer/list">{$ns.lm->getPhrase('Manufacturers')|default:'Manufacturers'}</a></li>
                    <li><a {if $ns.loadName=='recipient'}class="active"{/if} href="{$SITE_PATH}/recipient/list">{$ns.lm->getPhrase('Recipients')|default:'Recipients'}</a></li>
                    <li><a {if $ns.loadName=='cc'}class="active"{/if} href="{$SITE_PATH}/cc/list">{$ns.lm->getPhrase('CC')|default:'CC'}</a></li>
                    
                    <li><a {if $ns.loadName=='vproducts'}class="active"{/if} href="{$SITE_PATH}/vproducts/list">{$ns.lm->getPhrase('Vanilla Products')|default:'Vanilla Products'}</a></li>

                    <li><a {if $ns.loadName=='websites'}class="active"{/if} href="{$SITE_PATH}/websites/list">{$ns.lm->getPhrase('Online Shops')|default:'Online Shops'}</a></li>
                        {*                    <li><a {if $ns.loadName=='rorder'}class="active"{/if} href="{$SITE_PATH}/rorder/list?srt=order_date&ascdesc=DESC">Recipient Orders</a></li>*}
                    {/if}
                    {if !empty($ns.user) && $ns.user->getType() == 'level3'  || $ns.user->getType() == 'level2'}
                        <li><a {if $ns.loadName=='recipient'}class="active"{/if} href="{$SITE_PATH}/recipient/list">{$ns.lm->getPhrase('Recipients')|default:'Recipients'}</a></li>
                    {/if}
                
                
                    {if !empty($ns.user) && $ns.user->getType() == 'level3'}
                    <li><a {if $ns.loadName=='warehouse'}class="active"{/if} href="{$SITE_PATH}/warehouse">{$ns.lm->getPhrase('Warehouse')|default:'Warehouse'}</a></li>
                    {/if}
                    {if !empty($ns.user) && $ns.user->getType() == 'root' || $ns.user->getType() == 'level3'}
                        <li><a {if $ns.loadName=='giftcards'}class="active"{/if} href="{$SITE_PATH}/giftcards/list">{$ns.lm->getPhrase('Giftcards')|default:'Giftcards'}</a></li>
                    {/if}
                    {if !empty($ns.user) && $ns.user->getType() == 'root' || $ns.user->getType() == 'level2'}
                        <li><a {if $ns.loadName=='vanilla'}class="active"{/if} href="{$SITE_PATH}/vanilla/list">{$ns.lm->getPhrase('Vanilla')|default:'Vanilla'}</a></li>
                        <li><a {if $ns.loadName=='purse'}class="active"{/if} href="{$SITE_PATH}/purse/list">{$ns.lm->getPhrase('BTC-Products')|default:'BTC-Products'}</a></li>
                    {/if}
                    {if !empty($ns.user) && $ns.user->getType() == 'root' || $ns.user->getType() == 'checkout'}
                    <li><a {if $ns.loadName=='checkout'}class="active"{/if} href="{$SITE_PATH}/checkout/list">{$ns.lm->getPhrase('Checkout.am')|default:'Checkout.am'}</a></li>
                    <li><a {if $ns.loadName=='chusers'}class="active"{/if} href="{$SITE_PATH}/chusers/list">{$ns.lm->getPhrase('Checkout.am Users')|default:'Checkout.am Users'}</a></li>
                    {/if}
                    {if !empty($ns.user) && $ns.user->getType() == 'root'}
                    <li><a {if $ns.loadName=='warranty'}class="active"{/if} href="{$SITE_PATH}/warranty">{$ns.lm->getPhrase('Warranty')|default:'Warranty'}</a></li>
                    <li><a {if $ns.loadName=='settings'}class="active"{/if} href="{$SITE_PATH}/settings">{$ns.lm->getPhrase('Settings')|default:'Settings'}</a></li>
                    {/if}
                {/if}
            <li><a href="{$SITE_PATH}/logout">{$ns.lm->getPhrase('Logout')|default:'Logout'}</a></li>
        </ul>
    </div>
</div>
