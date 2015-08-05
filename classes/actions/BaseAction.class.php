<?php

namespace crm\actions {

    use crm\security\RequestGroups;
    use ngs\framework\AbstractAction;

    abstract class BaseAction extends AbstractAction {

        public function __construct() {
            
        }

        public function getRequestGroup() {
            return RequestGroups::$guestRequest;
        }

    }

}
