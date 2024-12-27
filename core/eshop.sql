-- Active: 1715285852305@@127.0.0.1@3306@eshop

create database eshop;

-- TABLES

select * from `catalog`;

create table if not exists `catalog` (
  `id` int not null auto_increment primary key,
  `title` varchar (255) not null default '-',
  `author` varchar(255) not null default '-',
  `price` int not null,
  `pubyear` int not null
);

drop table orders;
create table if not exists `orders` (
  `id` int not null auto_increment primary key,
  `order_id` varchar(250) not null unique,
  `customer` varchar(150) not null,
  `email` varchar(320) not null,
  `phone` varchar(16) not null,
  `address` varchar(300) not null,
  `created` timestamp default current_timestamp
    ON UPDATE CURRENT_TIMESTAMP
);

create table if not exists `ordered_items` (
  `id` int not null auto_increment primary key,
  `order_id` varchar(250) not null references `orders`(`order_id`)
    on delete restrict
    on update cascade,
  `item_id` int not null references `catalog`(`id`)
    on delete restrict
    on update cascade,
  `quantity` int
);

drop table `ordered_items`;

create table if not exists `admins` (
  `id` int not null auto_increment primary key,
  `login` varchar(255) not null,
  `password` varchar(255) not null,
  `email` varchar(320) not null,
  `created` timestamp default current_timestamp
    ON UPDATE CURRENT_TIMESTAMP
);

-- PROCEDURES

DELIMITER $$
create procedure spAddItemToCatalog(p_title varchar(255), p_author varchar(255), p_price int, p_pubyear int) 
begin
    insert into `catalog` (`title`, `author`, `price`, `pubyear`)
    values (p_title, p_author, p_price, p_pubyear);
end;
DELIMITER $;

DELIMITER $$
create procedure spGetCatalog()
begin
    select `id`, `title`, `author`, `price`, `pubyear`
      from `catalog`;
end;
DELIMITER $;

call `spGetCatalog`();

DELIMITER $$
create procedure spGetItemsForBasket(ids varchar(255))
begin
    select `id`, `title`, `author`, `price`, `pubyear`
      from `catalog` where find_in_set(`id`, ids);
end;
DELIMITER $;
drop procedure spGetItemsForBasket;

select * from `catalog`;
call spGetItemsForBasket("1,3");

DELIMITER $$
create procedure spSaveOrder(p_order_id varchar(50), p_customer varchar(150), p_phone varchar(16),
                             p_email varchar(320), p_address varchar(300)) 
begin
    insert into `orders` (`order_id`, `customer`, `email`, `phone`, `address`)
    values (p_order_id, p_customer, p_email, p_phone, p_address);
end;
DELIMITER $;
drop procedure spSaveOrder;

select * from `orders`;
select * from `ordered_items`;

DELIMITER $$
create procedure spSaveOrderedItems(p_order_id varchar(50), p_item_id int, p_quantity int) 
begin
    insert into `ordered_items` (`order_id`, `item_id`, `quantity`)
    values (p_order_id, p_item_id, p_quantity);
end;
DELIMITER $;

DELIMITER $$
create procedure spGetOrders()
begin
    select `order_id` as `id`, `customer`, `email`, `phone`, `address`, UNIX_TIMESTAMP(`created`) as `date`
        from `orders`;
end;
DELIMITER $;
call spGetOrders();

DELIMITER $$
create procedure spGetOrderedItems(p_order_id varchar(50)) 
begin
    select `title`, `author`, `price`, `pubyear`, `quantity`
      from `ordered_items`
      inner join `catalog`
       on `catalog`.`id` = `ordered_items`.`item_id`
      where `ordered_items`.`order_id` = p_order_id;
end;
DELIMITER $;
call `spGetOrderedItems`('0c7725247398c021a100582990b8d2fc');

DELIMITER $$
create procedure spSaveAdmin(p_login varchar(255), p_password varchar(255), p_email varchar(320)) 
begin
    insert into `admins` (`login`, `password`, `email`)
    values (p_login, p_password, p_email);
end;
DELIMITER $;
select * from  `admins`;
call spGetAdmin('login');
call `spSaveAdmin`('login', '$2y$10$7wzV5KtoIv3wvbv18I1S/e7gqtdhRv6ymjctUt/kpnzwaqo6vr4IC', 'qwer@asdf.asdf');
DELIMITER $$
create procedure spGetAdmin (p_login varchar(255)) 
begin
    select `login`, `password` as `hash`, `email`
      from `admins`
    where `login` = p_login;
end;
DELIMITER $;
drop procedure `spGetAdmin`;