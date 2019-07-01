<?php

namespace crm\actions\login {

    use crm\managers\AdminManager;
    use crm\security\RequestGroups;
    use crm\security\UserGroups;
    use NGS;
    use ngs\framework\AbstractAction;

    class LoginAction extends AbstractAction {

        public function service() {
            $adminDto = AdminManager::getInstance()->getByUsernamePassword(NGS()->args()->getUsername(), md5(NGS()->args()->getPassword()));
            if (!$adminDto) {
                $this->redirect('login');
            }
            NGS()->getSessionManager()->login(UserGroups::$ADMIN, $adminDto->getId());
            if ($adminDto->getType() == 'checkout') {
                $this->redirect('checkout/list');
            }
            if ($adminDto->getType() == 'level3') {
                $this->redirect('recipient/list');
            }
            if ($adminDto->getType() == 'vanillaupdater') {
                $this->redirect('vanilla/list');
            }
            $this->redirect('');
        }

        public function getRequestGroup() {
            return RequestGroups::$guestRequest;
        }

    }

}
