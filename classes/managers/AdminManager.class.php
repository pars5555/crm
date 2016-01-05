<?php
/**
 * @author Levon Naghashyan
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2015
 * @package admin.managers
 * @version 1.0.0
 *
 */
namespace crm\managers {

  use ngs\framework\AbstractManager;
  use crm\dal\mappers\AdminMapper;
  class AdminManager extends AbstractManager {

    /**
     * @var singleton instance of class
     */
    private static $instance = null;

    /**
     * Returns an singleton instance of this class
     *
     * @param object $config
     * @param object $args
     * @return
     */
    public static function getInstance() {
      if (self::$instance == null) {
        self::$instance = new AdminManager();
      }
      return self::$instance;
    }

    /**
     * gets admin by username and password
     *
     * @param string $username
     * @param string $password
     * @return AdminDto or null if admin does not exist
     */
    public function getByUsernamePassword($username, $password) {
      return AdminMapper::getInstance()->getByUsernamePassword($username, md5($password));
    }
    
    /**
     * get user by id and hash
     *
     * @param int $userId
     * @param string $hash
     * @return AdminDto or null if admin does not exist
     */
    public function validate($userId, $hash) {
      return AdminMapper::getInstance()->validateUser($userId, $hash);
    }
    
    /**
     * do login user update hash and last login date
     *
     * @param int $userId
     * @return boolean
     */
    public function loginUser($userId) {
      $userHash = md5(time().$userId);
      $adminDto = AdminMapper::getInstance()->createDto();
      $adminDto->setId($userId);
      $adminDto->setHash($userHash);
      $adminDto->setLastLogin(date('Y-m-d H:i:s'));
      AdminMapper::getInstance()->updateByPK($adminDto);
      return $userHash;
    }

  }

}
