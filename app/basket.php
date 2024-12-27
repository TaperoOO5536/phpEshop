<?php
$books = Eshop::getItemsFromBasket();
if(!($books instanceof Iterator)) {
	echo BASKET_SHOW_ERROR;
	throw new Exception('Бага в коде!');
	exit;
}
if($books instanceof EmptyIterator){
	echo BASKET_SHOW_ERROR;
} 
?>

<p>Вернуться в <a href='/catalog'>каталог</a></p>
<h1>Ваша корзина</h1>
<table>
<tr>
	<th>N п/п</th>
	<th>Название</th>
	<th>Автор</th>
	<th>Год издания</th>
	<th>Цена, руб.</th>
	<th>Количество</th>
	<th>Удалить</th>
</tr>
<?php
$priceCount = 0;
$counter = 1;
foreach($books as $book):
	$priceCount += $book->price;
?>
<tr>
	<td><?=$counter?></td>
	<td><?=$book->title?></td>
	<td><?=$book->author?></td>
	<td><?=$book->pubyear?></td>
	<td><?=$book->price?></td>
	<td>1</td>
	<td>
		<a href="remove_item_from_basket?id=<?=$book->id?>">Удалить</a>
	</td>
</tr>
<?php
$counter += 1;
endforeach;
?>
</table>

<p>Всего товаров в корзине на сумму: <?=$priceCount?> руб.</p>

<div style="text-align:center">
	<input type="button" value="Оформить заказ!"
                      onclick="location.href='/create_order'" />
</div>