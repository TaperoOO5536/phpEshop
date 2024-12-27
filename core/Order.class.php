<?php
class Order {
  public $customer;
  public $email;
  public $phone;
  public $address;
  public $id;
  public $date;
  public $items;

  function __construct(array $params = []){
    if(!$params) return;
    $this->customer = $params['customer'];
    $this->email = $params['email'];
    $this->phone = $params['phone'];
    $this->address = $params['address'];
  }
}