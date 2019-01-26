<?php

/**
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2010-2015
 * @package loads.site.main
 * @version 2.0.0
 */

namespace crm\loads\main\whishlist {

    use crm\loads\AdminLoad;
    use crm\managers\AttachmentManager;
    use crm\managers\CurrencyManager;
    use crm\managers\ProductManager;
    use crm\managers\WhishlistManager;
    use NGS;

    class OpenLoad extends AdminLoad {

        public function load() {
            $this->initErrorMessages();
            $this->initSuccessMessages();
            $whishlistId = NGS()->args()->id;
            $whishlist = WhishlistManager::getInstance()->selectByPK($whishlistId);
            $this->addParam('whishlist', $whishlist);
        }

        public function getTemplate() {
            return NGS()->getTemplateDir() . "/main/whishlist/open.tpl";
        }

    }

}
