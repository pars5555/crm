<?php

/**
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2010-2015
 * @package loads.site.main
 * @version 2.0.0
 */

namespace crm\loads\main\onlineshops {

    use crm\loads\AdminLoad;
    use crm\managers\OnlineShopsManager;
    use crm\managers\PurseOrderManager;
    use NGS;

    class ListLoad extends AdminLoad {

        public function load() {
            $accountNames = PurseOrderManager::getInstance()->getAllAccountNames('all');
            
            foreach ($accountNames as $accountName){
                $accountName = trim($accountName);
                if (!empty($accountName)){
                    OnlineShopsManager::getInstance()->addRow($accountName);
                }
            }
            
            
            $rows = OnlineShopsManager::getInstance()->selectAdvance('*', [], 'index');
            $this->addParam('rows', $rows);
        }

        public function getTemplate() {
            return NGS()->getTemplateDir() . "/main/onlineshops/list.tpl";
        }

    }

}
