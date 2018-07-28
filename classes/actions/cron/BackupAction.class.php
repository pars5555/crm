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
            $dbhost = '127.0.0.1:3036';
            $dbuser = 'crm';
            $dbpass = 'crm123';
            $backup_file = 'crm.pc.am_' . date("Y-m-d-H-i-s") . '.gz';
            $command = "mysqldump --opt -h $dbhost -u $dbuser -p $dbpass " . "test_db | gzip > $backup_file";
            var_dump($command);exit;
            system($command);
        }


    }

}
