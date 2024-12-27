<?php
error_reporting(E_ALL);

const CATALOG_ITEM_ADD_OK = 'Товар добавлен в каталог';
const CATALOG_ITEM_ADD_ERROR = 'Ошибка при добавлении товара в каталог';
const CATALOG_SHOW_ERROR = 'Ошибка при показе каталога';
const ADD_TO_BASKET_ERROR = 'Ошибка при добавлении товара в корзину';
const ADD_TO_BASKET_OK = 'Товар добавлен в корзину';
const BASKET_SHOW_ERROR = 'Ошибка при показе корзины';
const REMOVE_FROM_BASKET_OK = 'Товар удалён из корзины';
const REMOVE_FROM_BASKET_ERROR = 'Ошибка при удалении товара из корзины';
const ORDER_ADD_OK = 'Заказ оформлен';
const ORDER_ADD_ERROR = 'Ошибка при оформлении заказа';
const ORDERS_SHOW_ERROR = 'Ошибка при показе списка заказов';
const BOOKS_IN_ORDER_SHOW_ERROR = 'Ошибка при показе списка товаров в заказе';
const USER_ADD_OK = 'Пользователь добавлен успешно';
const USER_ADD_ERROR = 'Ошибка при создании пользователя';
const USER_LOGIN_ERROR = 'Логин или пароль неверны';

require_once 'core/init.php';

require_once 'app/__header.php';
require_once 'app/__router.php';
require_once 'app/__footer.php';
