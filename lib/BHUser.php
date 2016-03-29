<?php

// Расширенные NoSQL данные о текущем пользователе

class BHUser extends BHProto {

  private static $instance;

  public static function _() {
    if(isset(self::$instance)) return self::$instance;
    self::$instance = new self();
    return self::$instance;
  }

  private $user, $userID, $userKey, $userParams;
  private function __construct() {
    global $USER;
    $this->user = $USER;
    $this->userID = $this->user->GetID();
    $this->userKey = 'BHUser/'.$this->userID;
    $this->getUserInfo();
  }

  public function __get($param) {
    return isset($this->userParams->{$param}) ? $this->userParams->{$param} : null;
  }

  public function __set($param, $value) {
    return $this->userParams->{$param} = $value;
  }

  public function __isset($param) {
    return isset($this->userParams->{$param});
  }

  public function save() {
    BHStorage::_()->save($this->userKey, serialize($this->userParams));
  }

  private function getUserInfo() {
    if(isset($this->userParams)) return;
    $this->userParams = BHStorage::_()->get($this->userKey);
    if(!isset($this->userParams) || !is_object($this->userParams)) $this->userParams = new StdClass();
  }

  public static function install() {
    BHFactory::install('BHStorage');
    BHProto::checkInstall('BHUser');
    return true;
  }

}