<?php
class Book{
    public $id;
    public $title;
    public $author;
    public $price;
    public $pubyear;

    public function __construct($item) {
      if(!$item) return;
      $this->id = $item['id'] ?? null;
      $this->title = $item['title'];
      $this->author = $item['author'];
      $this->price = $item['price'];
      $this->pubyear = $item['pubyear'];
    }
}