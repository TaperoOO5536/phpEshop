<?php

Eshop::cleanOrderData();
$order = new Order($_POST);
$result = Eshop::saveOrder($order);
if($result) {
  echo ORDER_ADD_OK;
  header('Refresh: 3, url=catalog');
} else {
  echo ORDER_ADD_ERROR;
  exit;
}