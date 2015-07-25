<?php

/**
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2010-2015
 * @package loads.site.main
 * @version 2.0.0
 */

namespace crm\loads\main {

use crm\loads\NgsLoad;
use crm\managers\PartnerManager;
use crm\security\RequestGroups;
use NGS;

    class PartnerLoad extends NgsLoad {

        public function load() {
            $this->initErrorMessages();
            $this->initSuccessMessages();
            $partnerId = NGS()->args()->id;
            $partners = PartnerManager::getInstance()->getPartnersListFull(['id', '=', $partnerId]);
            if (!empty($partners)) {
                $partner = $partners[0];
                $this->addParam('partner', $partner);
            }
        }

        public function getTemplate() {
            return NGS()->getTemplateDir() . "/main/partner/partner.tpl";
        }

        public function getRequestGroup() {
            return RequestGroups::$guestRequest;
        }

    }

}
