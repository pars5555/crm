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
use crm\managers\PartnerManager;
use NGS;

    class DeletePartnerAction extends BaseAction {

        public function service() {
            if (isset(NGS()->args()->id)) {
                $partnerId = NGS()->args()->id;
            } else {
                $_SESSION['error_message'] = 'Partner ID is missing';
                $this->redirect('partner/list');
            }
            $partnerManager = PartnerManager::getInstance();
            $partnerDto = $partnerManager->selectByPK($partnerId);
            if (!isset($partnerDto)) {
                $_SESSION['error_message'] = 'Partner with ID ' . NGS()->args()->id . ' does not exists.';
                $this->redirect('partner/list');
            }
            $partnerManager->deletePartnerFull($partnerId);
            $_SESSION['success_message'] = 'Partner Successfully deleted!';
            $this->redirect('partner/list');
        }

    }

}
