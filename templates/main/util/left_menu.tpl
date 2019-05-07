<div id="mainLeftPanel"  class="left--panel">
    <div class="left--panel--content">
        <h1 class="left--panel--title">
            <span>
                Pages
            </span>
        </h1>
        <ul>
            {if $ns.userType == $ns.userTypeAdmin}
                {if $ns.user->getType() == 'root'}
                    <li><a {if $ns.loadName=='general' || $ns.loadName=='default'}class="active"{/if} href="{$SITE_PATH}/general">General</a></li>
                    <li><a {if $ns.loadName=='preorder'}class="active"{/if} href="{$SITE_PATH}/preorder/list?srt=order_date&ascdesc=DESC">Preorders</a></li>
                    <li><a {if $ns.loadName=='whishlist'}class="active"{/if} href="{$SITE_PATH}/whishlist/list?srt=order_date&ascdesc=DESC">Whishlist</a></li>
                    <li><a {if $ns.loadName=='partner'}class="active"{/if}  href="{$SITE_PATH}/partner/list">Partners</a></li>
                    <li><a {if $ns.loadName=='sale'}class="active"{/if} href="{$SITE_PATH}/sale/list?srt=order_date&ascdesc=DESC">Sale Orders</a></li>
                    <li><a {if $ns.loadName=='purchase'}class="active"{/if} href="{$SITE_PATH}/purchase/list?srt=order_date&ascdesc=DESC">Purchase Orders</a></li>
                    <li><a {if $ns.loadName=='payment'}class="active"{/if} href="{$SITE_PATH}/payment/list?srt=date&ascdesc=DESC">Payment Order</a></li>
                    <li><a {if $ns.loadName=='billing'}class="active"{/if} href="{$SITE_PATH}/billing/list?srt=date&ascdesc=DESC">Billing Order</a></li>
                    <li><a {if $ns.loadName=='all'}class="active"{/if} href="{$SITE_PATH}/all">All Deals</a></li>
                    <li><a {if $ns.loadName=='warehouse'}class="active"{/if} href="{$SITE_PATH}/warehouse">Warehouse</a></li>
                    <li><a {if $ns.loadName=='rwarehouses'}class="active"{/if} href="{$SITE_PATH}/rwarehouses">Real Warehouses</a></li>
                    <li><a {if $ns.loadName=='pwarehouse'}class="active"{/if} href="{$SITE_PATH}/pwarehouse">Partner Warehouse</a></li>
                    <li><a {if $ns.loadName=='product'}class="active"{/if} href="{$SITE_PATH}/product/list">Products</a></li>
                    <li><a {if $ns.loadName=='manufacturer'}class="active"{/if} href="{$SITE_PATH}/manufacturer/list">Manufacturers</a></li>
                    <li><a {if $ns.loadName=='recipient'}class="active"{/if} href="{$SITE_PATH}/recipient/list">Recipients</a></li>
                    <li><a {if $ns.loadName=='websites'}class="active"{/if} href="{$SITE_PATH}/websites/list">Online Shops</a></li>
                        {*                    <li><a {if $ns.loadName=='rorder'}class="active"{/if} href="{$SITE_PATH}/rorder/list?srt=order_date&ascdesc=DESC">Recipient Orders</a></li>*}
                    <li><a {if $ns.loadName=='purse'}class="active"{/if} href="{$SITE_PATH}/purse/list">BTC-Products</a></li>
                    {/if}
                    {if $ns.user->getType() == 'root' || $ns.user->getType() == 'checkout'}
                    <li><a {if $ns.loadName=='checkout'}class="active"{/if} href="{$SITE_PATH}/checkout/list">Checkout.am</a></li>
                    <li><a {if $ns.loadName=='chusers'}class="active"{/if} href="{$SITE_PATH}/chusers/list">Checkout.am Users</a></li>
                    {/if}
                    {if $ns.user->getType() == 'root'}
                    <li><a {if $ns.loadName=='warranty'}class="active"{/if} href="{$SITE_PATH}/warranty">Warranty</a></li>
                    <li><a {if $ns.loadName=='settings'}class="active"{/if} href="{$SITE_PATH}/settings">Settings</a></li>
                    {/if}
                {/if}
            <li><a href="{$SITE_PATH}/logout">Logout</a></li>
        </ul>
    </div>
</div>
