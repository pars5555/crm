<?php

/**
 * main site action for all ngs site's actions
 *
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2009-2014
 * @package actions.site
 * @version 6.0
 *
 */

namespace crm\actions\main\recipient {

    use crm\actions\BaseAction;
    use crm\managers\CurrencyManager;
    use crm\managers\RecipientManager;
    use NGS;
    use ngs\framework\exceptions\NgsErrorException;

    class GetRecipientDebtAction extends BaseAction {

        public function service() {
            if (!isset(NGS()->args()->recipient_id)) {
                new NgsErrorException('Missing Recipient ID!');
            }
            $recipient_id = intval(NGS()->args()->recipient_id);
            $recipientDto = RecipientManager::getInstance()->selectByPk($recipient_id);
            if (!isset($recipientDto)) {
                new NgsErrorException('Recipient does not exist with given ID: ' . NGS()->args()->recipient_id);
            }
            $debt = RecipientManager::getInstance()->calculateRecipientDebtBySalePurchaseAndPaymentTransations($recipient_id);
            $currenciesMappedById = CurrencyManager::getInstance()->selectAdvance('*', ['active', '=', 1], null, null, null, null, true);

            
            foreach ($debt as $currencyId => $amount) {
                $currencyDto = $currenciesMappedById[$currencyId];
                $this->addParam($currencyDto->getIso(), [$amount, $currencyDto->getTemplateChar(), $currencyDto->getSymbolPosition()]);
            }
        }

    }

}
