<?php

if(isset($_GET['id'])) {
  Eshop::addItemToBasket($_GET['id']);
  echo ADD_TO_BASKET_OK;
  header('Refresh: 3, url=catalog');
} else {
  echo ADD_TO_BASKET_ERROR;
  exit;
}