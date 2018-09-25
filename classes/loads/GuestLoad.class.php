<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SendchatLoad
 *
 * @author Administrator
 */

namespace crm\loads {

    use crm\security\RequestGroups;

    abstract class GuestLoad extends NgsLoad {

        public function load() {
            
        }

        public function getRequestGroup() {
            return RequestGroups::$guestRequest;
        }

    }

}
