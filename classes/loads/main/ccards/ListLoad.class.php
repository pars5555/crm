<?php

/**
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2010-2015
 * @package loads.site.main
 * @version 2.0.0
 */

namespace crm\loads\main\ccards {

    use crm\loads\AdminLoad;
    use crm\managers\CreditCardsManager;
    use NGS;

    class ListLoad extends AdminLoad {

        public function load() {
            self::initLoad($this);
        }

        public static function initLoad($load) {
            $rows = CreditCardsManager::getInstance()->selectAdvance('*', [], 'id');
            $load->addParam('rows', $rows);
        }

        public function getTemplate() {
            return NGS()->getTemplateDir() . "/main/ccards/list.tpl";
        }

    }

}
