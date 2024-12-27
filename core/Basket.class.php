<?php
class Basket {
  private static $basket = [];
  private static $count = 0;
  private static $orderId = null;

  const NAME = 'basket';

  public static function init(){
    if(isset($_COOKIE[self::NAME])) {
      self::read($_COOKIE[self::NAME]);
    } else {
      self::create();
    }
  }

  private static function create(){
    self::$orderId = bin2hex(random_bytes(16));
    self::save();
  }

  private static function save(){
    self::$basket['order-id'] = self::$orderId;
    $basket = base64_encode(serialize(self::$basket));
    setcookie(self::NAME, $basket, 0x7FFFFFFF);
  }

  private static function read(string $basket){
    self::$basket = unserialize(base64_decode($basket));
    self::$orderId = self::$basket['order-id'];
    unset(self::$basket['order-id']);
    self::$count = count(self::$basket);
  }

  public static function add($id, $quantity=1){
    self::$basket[$id] = $quantity;
    self::save();
  }

  public static function remove($id){
    unset(self::$basket[$id]);
    self::save();
  }

  public static function get(): iterable{
    return new ArrayIterator(self::$basket);
  }

  public static function getOrderId(): string{
    return self::$orderId;
  }
  
  public static function quantity($id){
    return self::$basket[$id];
  }

  public static function size(): int{
    return self::$count;
  }

  public static function clear(){
    self::$basket = [];
    self::$count = 0;
    self::create();
    unset($_COOKIE[self::NAME]);
    // setcookie(self::NAME, 'deleted', time()-3600);
    // self::save();
  }
}
?>