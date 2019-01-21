<div class="container preorder--list--container">
    <h1 class="main_title">Preorders</h1>

    {if isset($ns.error_message)}
        {include file="{ngs cmd=get_template_dir}/main/message.tpl" type="error" content="{$ns.error_message}"}
    {/if}
    {if isset($ns.success_message)}
        {include file="{ngs cmd=get_template_dir}/main/message.tpl" type="success" content="{$ns.success_message}"}
    {/if}

    {include file="{ngs cmd=get_template_dir}/main/preorder/list_filters.tpl"}
    <div class="main-table">
        <table>
            <tr>
                <th>Id</th>
                <th>Partner</th>
                <th>Date</th>
                <th>Payment Deadline</th>
                <th>Total Amount</th>
                <th>Note</th>
                <th class="icon-cell">View</th>
                <th class="icon-cell">Edit</th>
                <th class="icon-cell">Delete</th>
            </tr>
            {foreach from=$ns.preorders item=preorder}
                <tr {if $preorder->getCancelled() == 1}style="color: gray"{/if}>
                    <td class="link-cell id">
                        {if isset($ns.attachments[$preorder->getId()])}
                            <img src="{$SITE_PATH}/img/attachment.png" width="32"/>
                        {/if}
                        <a href="{$SITE_PATH}/preorder/{$preorder->getId()}">
                            <span>{$preorder->getId()} </span>
                        </a>
                    </td>
                    <td>{$preorder->getPartnerDto()->getName()}</td>
                    <td>{$preorder->getOrderDate()}</td>
                    <td style="{if $smarty.now|date_format:"%Y-%m-%d">=$preorder->getPaymentDeadline() && $preorder->getPaid()==0}color:red{/if}">{$preorder->getPaymentDeadline()}</td>
                    {assign totalAmount $preorder->getTotalAmount()}
                    <td>
                        {foreach from=$totalAmount key=currencyId item=amount}
                            <span class="price">
                                {assign currencyDto $ns.currencies[$currencyId]}
                                {if $currencyDto->getSymbolPosition() == 'left'}
                                    {$currencyDto->getTemplateChar()}
                                {/if}
                                {$amount}
                                {if $currencyDto->getSymbolPosition() == 'right'}
                                    {$currencyDto->getTemplateChar()}
                                {/if}
                            </span>
                        {/foreach}
                    </td>
                    <td>{$preorder->getNote()}</td>
                    <td class="icon-cell">
                        <a href="{$SITE_PATH}/preorder/{$preorder->getId()}">
                            <span class="button_icon" title="View">
                                <i class="fa fa-eye"></i>
                            </span>
                        </a>
                    </td>
                    <td class="icon-cell">
                        <a href="{$SITE_PATH}/preorder/edit/{$preorder->getId()}">
                            <span class="button_icon" title="Edit">
                                <i class="fa fa-pencil"></i>
                            </span>
                        </a>
                    </td>
                    <td class="icon-cell">
                        <a class="deletePreorder" href="{$SITE_PATH}/dyn/main_preorder/do_delete_preorder?id={$preorder->getId()}">
                            <span class="button_icon" title="delete">
                                <i class="fa fa-trash-o"></i>
                            </span>
                        </a>
                    </td>
                </tr>
            {/foreach}
        </table>
    </div>
</div>