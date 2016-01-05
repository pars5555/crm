<?php

/**
 * Contains definitions for all participant requests in system.
 *
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2009-2014
 * @package security
 * @version 6.0
 *
 */

namespace crm\security {

    class RequestGroups {

        public static $adminRequest = 1;
        public static $moderatorRequest = 3;
        public static $observerRequest = 5;
        public static $guestRequest = 11;

    }

}
