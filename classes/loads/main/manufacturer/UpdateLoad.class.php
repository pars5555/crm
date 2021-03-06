<?php

/**
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2010-2015
 * @package loads.site.main
 * @version 2.0.0
 */

namespace crm\loads\main\manufacturer {

    use crm\loads\AdminLoad;
    use crm\managers\ManufacturerManager;
    use crm\security\RequestGroups;
    use NGS;

    class UpdateLoad  extends AdminLoad {

        public function load() {
            $this->initErrorMessages();
            $this->initSuccessMessages();
            $id = intval(NGS()->args()->id);
            $manufacturer = ManufacturerManager::getInstance()->selectByPk($id);
            if (isset($manufacturer)) {
                if (!isset($_SESSION['action_request'])) {
                    $_SESSION['action_request'] = ['name' => $manufacturer->getName(), 'link' => $manufacturer->getLink()];
                }
                $this->addParam("manufacturer", $manufacturer);
                $this->addParam('req', $_SESSION['action_request']);
                unset($_SESSION['action_request']);
            }
        }

        public function getTemplate() {
            return NGS()->getTemplateDir() . "/main/manufacturer/update.tpl";
        }

    }

}
