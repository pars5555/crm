<?php

/**
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2010-2015
 * @package loads.site.main
 * @version 2.0.0
 */

namespace crm\loads\main\partner {

    use crm\loads\AdminLoad;
    use crm\managers\CurrencyManager;
    use crm\managers\PartnerManager;
    use crm\security\RequestGroups;
    use NGS;

    class UpdateLoad  extends AdminLoad {

        public function load() {
            $this->initErrorMessages();
            $this->initSuccessMessages();
            $id = intval(NGS()->args()->id);
            $partners = PartnerManager::getInstance()->getPartnersFull(['id', '=', $id]);
            if (!empty($partners)) {
                $this->addParam('currencies', CurrencyManager::getInstance()->selectAdvance('*', ['active', '=', 1], ['name']));
                $partner = $partners[0];
                if (!isset($_SESSION['action_request'])) {
                    $_SESSION['action_request'] = [
                        'name' => $partner->getName(),
                        'email' => $partner->getEmail(),
                        'address' => $partner->getAddress(),
                        'phone' => $partner->getPhone(),
                        'telegram_chat_ids' => $partner->getTelegramChatIds(),
                        'initial_debt' => $partner->getPartnerInitialDebtDtos()
                    ];
                }
                $this->addParam("partner", $partner);
                $this->addParam('req', $_SESSION['action_request']);
                unset($_SESSION['action_request']);
            }
        }

        public function getTemplate() {
            return NGS()->getTemplateDir() . "/main/partner/update.tpl";
        }


    }

}
