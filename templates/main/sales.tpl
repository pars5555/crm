<div>
    {include file="{getTemplateDir}/main/left_menu.tpl"}
    <div>
        <a class="button" href="javascript:void(0);">create</a>
        <a class="button" href="javascript:void(0);">cancel</a>
    </div>
    <form class="createSaleOrder" autocomplete="off">
        <div>
            <label>Order Date</label>
            {html_select_date prefix='orderDate'}
        </div>
        <div>
            <label>Partner</label>
            <select>
                {foreach from=$ns.partners item=p}
                    <option>{$p->getName()}</option>
                {/foreach}
            </select>
        </div>
        <div>
            <label>Payment</label>
            <select>
                {foreach from=$ns.payment_methods item=pm}
                    <option>{$pm->getName()}</option>
                {/foreach}
            </select>
        </div>
        <div>
            <label>Note</label>
            <textarea type="text" name="note"></textarea>
        </div>
        <div class="saleOrderLine">
            <table border="3" style="width: 500px">
                <thead>
                <th>Heading 1</th>
                <th>Heading 2</th>
                </thead>
                <tbody>
                    <tr>
                        <td></td>
                        <td ></td>
                    </tr>

                </tbody>
            </table>
        </div>
        <input type="submit" value="Create"/>
    </form>
</div>
