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

namespace crm\actions\main\whishlist {

    use crm\actions\BaseAction;
    use crm\managers\WhishlistManager;
    use NGS;

    class DeleteWhishlistAction extends BaseAction {

        public function service() {
            if (isset(NGS()->args()->id)) {
                $whishlistId = NGS()->args()->id;
            } else {
                $_SESSION['error_message'] = 'Whishlist Order ID is missing';
                $this->redirect('whishlist/list');
            }
            $whishlistManager = WhishlistManager::getInstance();
            $whishlistManager->deleteByPK($whishlistId);
            $_SESSION['success_message'] = 'Whishlist Order Successfully deleted!';
            if (strpos($_SERVER['HTTP_REFERER'], 'whishlist/list') === false) {
                $this->redirect('whishlist/list');
            } else {
                $this->redirectToReferer();
            }
        }

    }

}
