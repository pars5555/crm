<?php

/**
 * @author Levon Naghashyan
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2015
 * @package admin.dal.dto
 * @version 1.0.0
 *
 */

namespace crm\dal\dto {

    use ngs\framework\dal\dto\AbstractDto;

    class AttachmentDto extends AbstractDto {

        // Map of DB value to Field value
        protected $mapArray = array("id" => "id", 
            "partner_id" => "partner_id", "entity_name" => "entity_name", 
            "entity_id" => "entityId", "uploaded_file_name"=>"uploadedFileName","file_name"=>"fileName", "created_at" => "createdAt"
            );

        // returns map array
        public function getMapArray() {
            return $this->mapArray;
        }
        
        public function getImageHref() {
            if ($this->getEntityName() === 'checkout'){
                $order = \crm\managers\PurseOrderManager::getInstance()->selectByPk($this->getEntityId());
                $checkoutOrderId = $order->getCheckoutOrderId();
                if (empty($checkoutOrderId)) {
                    return "/img/attachment.png";
                }
                $fileName = $this->getFileName();
                return "https://checkout.am/img/wh/$checkoutOrderId/pictures/$fileName";
            }
        }


    }

}
