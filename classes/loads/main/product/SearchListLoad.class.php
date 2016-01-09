<?php

/**
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2010-2015
 * @package loads.site.main
 * @version 2.0.0
 */

namespace crm\loads\main\product {

    use crm\loads\AdminLoad;
    use crm\managers\ProductManager;
    use crm\security\RequestGroups;
    use NGS;

    class SearchListLoad  extends AdminLoad {

        public function load() {
            $products = [];
            if (!empty($_REQUEST['searchText'])) {
                $searchText = $_REQUEST['searchText'];
                $words = explode(' ', $searchText);
                $whereArray = [];
                foreach ($words as $key => $word) {
                    $whereArray[] = 'name';
                    $whereArray[] = 'like';
                    $whereArray[] = "'%" . $word . "%'";
                    if ($key < count($words) - 1) {
                        $whereArray[] = 'AND';
                    }
                }
                $products = ProductManager::getInstance()->selectAdvance('*', $whereArray, 'name', 'ASC', 0, 1000);
            }
            $this->addParam('products', $products);
        }

        public function getTemplate() {
            return NGS()->getTemplateDir() . "/main/product/nest_search_list.tpl";
        }


    }

}
            