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

    class GetCryptoRatesAction extends BaseAction {

        public function service() {
            $data = json_decode(file_get_contents('https://blockchain.info/ticker'), true);
            \crm\managers\CryptoRateManager::getInstance()->addRow('BTC', $data['USD']['last']);
            
        }

    }

}
