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

namespace crm\actions\main\product {

    use crm\actions\BaseAction;
    use NGS;

    class ReserveProductAction extends BaseAction {

        public function service() {
            if ($this->validateFormData()) {
                list($id, $qty, $phoneNumber, $hours, $note) = $this->getFormData();
                
                \crm\managers\ProductReservationManager::getInstance()->addRow($id, $qty, $phoneNumber, $hours, $note);
            }
        }

        private function getFormData() {
            $product_id = NGS()->args()->product_id;
            $qty = NGS()->args()->qty;
            $phoneNumber = NGS()->args()->phone_number;
            $hours = NGS()->args()->hours;
            $note = NGS()->args()->note;
            return [$product_id, $qty, $phoneNumber, $hours, $note];
        }

        private function validateFormData() {
            if (empty(NGS()->args()->product_id)) {
                $this->addParam('success', false);
                $this->addParam('message', 'Missing Procut ID');
                return false;
            }
            if (empty(NGS()->args()->phone_number) || strlen(NGS()->args()->phone_number) < 8) {
                $this->addParam('success', false);
                $this->addParam('message', 'Please input valid phone number');
                return false;
            }
            $this->addParam('success', true);
            return true;
        }

    }

}
