<?php

/**
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2010-2015
 * @package loads.site.main
 * @version 2.0.0
 */

namespace crm\loads\main\backup {

    use crm\loads\AdminLoad;
    use crm\security\RequestGroups;
    use NGS;

    class BackupLoad  extends AdminLoad {

        public function load() {
            
        }

        public function getTemplate() {
            return NGS()->getTemplateDir() . "/main/backup/backup.tpl";
        }


    }

}
