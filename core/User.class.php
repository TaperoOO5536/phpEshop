<?php
class User {
  public $id = 0;
  public $login;
  public $email;
  public $password;
  public $created;

  public function __construct($params) {
    if (!$params) return;
    $this->login = $params['login'];
    $this->password = $params['password'];
    $this->email = $params['email'] ?? '';
  }
}