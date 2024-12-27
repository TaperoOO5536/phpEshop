<?php
$books = Eshop::getItemsFromCatalog();
if(!($books instanceof Iterator)) {
	echo CATALOG_SHOW_ERROR;
	throw new Exception('Бага в коде!');
	exit;
}
if($books instanceof EmptyIterator){
	echo CATALOG_SHOW_ERROR;
}
$count = Basket::size();
?>

<h1>Каталог товаров</h1>
<p class='admin'><a href='admin'>админка</a></p>
<p>Товаров в <a href='basket'>корзине</a>: <?=$count?></p>
<table>
<tr>
	<th>Название</th>
	<th>Автор</th>
	<th>Год издания</th>
	<th>Цена, руб.</th>
	<th>В корзину</th>
</tr>
<?php
foreach($books as $book):
?>
<tr>
	<td><?=$book->title?></td>
	<td><?=$book->author?></td>
	<td><?=$book->pubyear?></td>
	<td><?=$book->price?></td>
	<td>
		<a href="add_item_to_basket?id=<?=$book->id?>">В корзину</a>
	</td>
</tr>
<?php
endforeach;
?>
</table>