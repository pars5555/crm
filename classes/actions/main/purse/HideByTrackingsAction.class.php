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

namespace crm\actions\main\purse {

    use crm\actions\BaseAction;
    use crm\managers\PurseOrderManager;
    use NGS;

    class HideByTrackingsAction extends BaseAction {

        public function service() {
            $strackingNumbersStr = trim(NGS()->args()->trackingNumbers);
            $strackingNumbersStr = preg_replace('/\s+/', ';', $strackingNumbersStr);
            $strackingNumbersStr = str_replace(',', ';', $strackingNumbersStr);
            $strackingNumbersArray = explode(";", $strackingNumbersStr);
            $rows = \crm\managers\PurseOrderManager::getInstance()->findByTrackingNumbers($strackingNumbersArray, false);
            foreach ($rows as $row) {
                if ($row->getId() > 0){
                    PurseOrderManager::getInstance()->updateField($row->getId(), 'hidden', 1);
                }
            }
            
        }

    }

}
