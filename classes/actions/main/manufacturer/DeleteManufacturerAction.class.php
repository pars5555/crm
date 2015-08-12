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

namespace crm\actions\main\manufacturer {

    use crm\actions\BaseAction;
    use crm\managers\ManufacturerManager;
    use NGS;

    class DeleteManufacturerAction extends BaseAction {

        public function service() {
            if (isset(NGS()->args()->id)) {
                $manufacturerId = NGS()->args()->id;
            } else {
                $_SESSION['error_message'] = 'Manufacturer ID is missing';
                $this->redirect('manufacturer/list');
            }
            $manufacturerManager = ManufacturerManager::getInstance();
            $partnerDto = $manufacturerManager->selectByPK($manufacturerId);
            if (!isset($partnerDto)) {
                $_SESSION['error_message'] = 'Manufacturer with ID ' . NGS()->args()->id . ' does not exists.';
                $this->redirect('manufacturer/list');
            }
            $manufacturerManager->deletePartnerFull($manufacturerId);
            $_SESSION['success_message'] = 'Manufacturer Successfully deleted!';
            $this->redirect('manufacturer/list');
        }

    }

}
