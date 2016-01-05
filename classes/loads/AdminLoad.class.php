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
    use NGS;
    use ngs\framework\exceptions\NgsErrorException;

    abstract class AdminLoad extends NgsLoad {

        public function onNoAccess() {
            if (NGS()->getHttpUtils()->isAjaxRequest()) {
                throw new NgsErrorException("session expire", 1);
            }
            $this->redirect("login");
        }

        public function getRequestGroup() {
            return RequestGroups::$adminRequest;
        }

    }

}