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

namespace crm\actions\main\partner {

    use crm\actions\BaseAction;
    use crm\managers\CurrencyManager;
    use crm\managers\PartnerManager;
    use NGS;
    use ngs\framework\exceptions\NgsErrorException;

    class GetPartnerDebtAction extends BaseAction {

        public function service() {
            if (!isset(NGS()->args()->partner_id)) {
                new NgsErrorException('Missing Partner ID!');
            }
            $partner_id = intval(NGS()->args()->partner_id);
            $partnerDto = PartnerManager::getInstance()->selectByPK($partner_id);
            if (!isset($partnerDto)) {
                new NgsErrorException('Partner does not exist with given ID: ' . NGS()->args()->partner_id);
            }
            $debt = PartnerManager::getInstance()->calculatePartnerDebtBySalePurchaseAndPaymentTransations($partner_id);
            $currenciesMappedById = CurrencyManager::getInstance()->selectAdvance('*', ['active', '=', 1], null, null, null, null, true);

            
            foreach ($debt as $currencyId => $amount) {
                $currencyDto = $currenciesMappedById[$currencyId];
                $this->addParam($currencyDto->getIso(), [$amount, $currencyDto->getTemplateChar(), $currencyDto->getSymbolPosition()]);
            }
        }

    }

}
