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

namespace crm\actions\cron {

    use crm\actions\BaseAction;

    class BackupAction extends BaseAction {

        public function service() {
            $backup_file = 'crm.pc.am_' . date("Y-m-d-H-i-s") . '.gz';
            system("mysqldump --opt -h localhost -P 3306 -u crm -pcrm123 crm.pc.am | gzip > $backup_file");
        }

    }

}
