<?php

/**
 * TracksDto mapper class
 * setter and getter generator
 * for ilyov_tracks table
 *
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2009-2015
 * @package dal.dto.tracks
 * @version 6.0
 *
 */

namespace crm\dal\dto {

    use \ngs\framework\dal\dto\AbstractDto;

    class ProductDto extends AbstractDto {

        // constructs class instance
        public function __construct() {
            
        }

        // Map of DB value to Field value
        private $mapArray = array("id" => "id", "name" => "name", "model" => "model", "category_id" => "categoryId", "location_note"=>"locationNote", "image_url" => "imageUrl",
            "manufacturer" => "manufacturer", "uom_id" => "uomId", "unit_cost" => "unitCost","unit_weight" => "unitWeight", "hidden" => "hidden"
            , "stock_price" => "stockPrice", "sale_price" => "salePrice","list_am_price" => "listAmPrice", "include_in_price_xlsx" => "includeInPriceXlsx");

        // returns map array
        public function getMapArray() {
            return $this->mapArray;
        }

    }

}
