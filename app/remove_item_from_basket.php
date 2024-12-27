<?php

if(isset($_GET['id'])) {
  Eshop::removeItemFromBasket($_GET['id']);
  echo REMOVE_FROM_BASKET_OK;
  header('Refresh: 3, url=basket');
} else {
  echo REMOVE_FROM_BASKET_ERROR;
  exit;
}