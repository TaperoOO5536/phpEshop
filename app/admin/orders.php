<?php
$orders = Eshop::getOrders();
if(!($orders instanceof Iterator)) {
	echo ORDERS_SHOW_ERROR;
	throw new Exception('Бага в коде!');
	exit;
}
if($orders instanceof EmptyIterator){
	echo ORDERS_SHOW_ERROR;
}
?>

<h1>Поступившие заказы:</h1>
<a href='/admin'>Назад в админку</a>
<hr>
<?php
foreach($orders as $order):
?>
<h2>Заказ номер: <?=$order->id?></h2>
<p><b>Заказчик</b>: <?=$order->customer?></p>
<p><b>Email</b>: <?=$order->email?></p>
<p><b>Телефон</b>: <?=$order->phone?></p>
<p><b>Адрес доставки</b>: <?=$order->address?></p>
<p><b>Дата размещения заказа</b>: <?=$order->date?></p>

<h3>Купленные товары:</h3>
<?php
if(!($order->items instanceof Iterator)) {
	echo BOOKS_IN_ORDER_SHOW_ERROR;
	throw new Exception('Бага в коде!');
	exit;
}
if($order->items instanceof EmptyIterator):
	echo BOOKS_IN_ORDER_SHOW_ERROR;
else:
?>
<table>
<tr>
	<th>N п/п</th>
	<th>Название</th>
	<th>Автор</th>
	<th>Год издания</th>
	<th>Цена, руб.</th>
	<th>Количество</th>
</tr>
<?php
$priceCount = 0;
$count = 1;
foreach($order->items as $book):
?>
<tr>
	<td><?=$count?></td>
	<td><?=$book->title?></td>
	<td><?=$book->author?></td>
	<td><?=$book->pubyear?></td>
	<td><?=$book->price?></td>
	<td>1</td>
</tr>
<?php
$count += 1;
$priceCount += $book->price;
endforeach;
?>
</table>
<p>Всего товаров в заказе на сумму: <?=$priceCount?> руб.</p>
<hr>
<?php
endif;
endforeach;
?>