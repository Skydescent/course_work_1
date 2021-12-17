-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Апр 07 2020 г., 12:46
-- Версия сервера: 5.7.25
-- Версия PHP: 7.3.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `fashion`
--

-- --------------------------------------------------------

--
-- Структура таблицы `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(60) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Дамп данных таблицы `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Все'),
(2, 'Женщины'),
(3, 'Мужчины'),
(4, 'Дети'),
(5, 'Аксессуары');

-- --------------------------------------------------------

--
-- Структура таблицы `groups`
--

CREATE TABLE `groups` (
  `id` int(11) NOT NULL,
  `name` varchar(45) COLLATE utf8_bin NOT NULL,
  `description` text COLLATE utf8_bin
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Дамп данных таблицы `groups`
--

INSERT INTO `groups` (`id`, `name`, `description`) VALUES
(1, 'admin', 'Доступен административный интерфейс, список заказов, управление товарами'),
(2, 'operator', 'Доступен административный интерфейс, список заказов'),
(3, 'registered', NULL),
(4, 'guest', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `created_at` date DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `user_name` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `user_surname` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `user_thirdname` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `user_phone` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `user_email` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `delivery` tinyint(1) NOT NULL,
  `delivery_cost` decimal(10,2) NOT NULL,
  `city` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `street` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `home` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `aprt` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `pay` varchar(45) COLLATE utf8_bin NOT NULL,
  `comment` text COLLATE utf8_bin,
  `order_cost` decimal(10,2) NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Дамп данных таблицы `orders`
--

INSERT INTO `orders` (`id`, `created_at`, `user_id`, `user_name`, `user_surname`, `user_thirdname`, `user_phone`, `user_email`, `delivery`, `delivery_cost`, `city`, `street`, `home`, `aprt`, `pay`, `comment`, `order_cost`, `status`) VALUES
(7, '2020-03-02', NULL, 'Кирилл', 'Калашников', 'фыва', '89234108580', 'kirill310587@mail.ru', 1, '280.00', 'Nahariya', 'Израиль', '22', '23', 'cash', 'вафыва', '5280.99', 0),
(8, '2002-03-20', NULL, 'Кирилл', 'Калашников', 'фыв', '89234108580', 'kirill310587@mail.ru', 1, '280.00', 'Томск', 'Сибирская 102/3, 103', '34', '55', 'cash', 'фывафыва', '25715.00', 1),
(9, '2020-03-21', NULL, 'Кирилл', 'Калашников', 'Вит', '89234108580', 'kirill310587@mail.ru', 0, '0.00', '', '', '', '', 'cash', 'фывафыв', '14990.89', 0),
(10, '2020-03-22', NULL, 'Вера', 'Прима', '', '515-616', 'prima@vera.com', 0, '0.00', '', '', '', '', 'card', '', '25435.00', 0),
(11, '2020-03-23', NULL, 'Кана', 'Пунта', '', '818922', 'punta@800.cana', 0, '0.00', '', '', '', '', 'card', '', '25435.00', 0),
(12, '2020-03-24', NULL, 'Круз', 'Санта', '', '200400', 'santa@cruz.ru', 0, '0.00', '', '', '', '', 'card', '', '14990.89', 0),
(13, '2020-03-25', NULL, 'Гуд', 'Робин', '', '999-999', 'good@sharewood.com', 1, '280.00', 'Шервуд', 'GoodStr', '12', '12', 'card', 'GOOD', '403.50', 0),
(17, '2020-03-29', NULL, 'Виктор', 'Викторов', '', '900-000', 'vik@tor.vik', 1, '280.00', 'Марков', 'Арнаутская', '12', '12', 'card', 'Привет!', '403.50', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `order_product`
--

CREATE TABLE `order_product` (
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_estonian_ci;

--
-- Дамп данных таблицы `order_product`
--

INSERT INTO `order_product` (`order_id`, `product_id`) VALUES
(13, 2),
(17, 2),
(8, 3),
(10, 3),
(11, 3),
(9, 6),
(12, 6);

-- --------------------------------------------------------

--
-- Структура таблицы `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(60) COLLATE utf8_bin NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text COLLATE utf8_bin,
  `img_path` varchar(80) COLLATE utf8_bin NOT NULL,
  `is_new` tinyint(1) NOT NULL DEFAULT '0',
  `is_sale` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Дамп данных таблицы `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `description`, `img_path`, `is_new`, `is_sale`, `is_active`) VALUES
(1, 'Шорты джинсовые23', '2999.00', NULL, 'img/products/product-1.jpg', 1, 1, 0),
(2, 'Шорты классик', '123.50', NULL, 'img/products/product-2.jpg', 1, 0, 1),
(3, 'Часы с ремешком кожа', '26435.00', NULL, 'img/products/product-3.jpg', 1, 1, 1),
(4, 'Штаны универсальные', '934.12', NULL, 'img/products/product-4.jpg', 1, 1, 1),
(5, 'Сумка Злой крокодил', '31100.44', NULL, 'img/products/product-5.jpg', 1, 0, 1),
(6, 'Платье простое 11', '14990.89', NULL, 'img/products/product-6.jpg', 1, 1, 1),
(7, 'Пальто из единорога', '22324.66', NULL, 'img/products/product-7.jpg', 1, 0, 1),
(8, 'Джинсы Старателя', '12344.35', NULL, 'img/products/product-8.jpg', 1, 1, 1),
(9, 'Ботильоны дикий Дермантин', '19200.99', NULL, 'img/products/product-9.jpg', 0, 0, 1),
(10, 'Юбка карандаш', '5600.00', NULL, 'img/products/юбка карандаш.jpg', 1, 1, 1),
(11, 'Свитер', '1350.00', NULL, 'no_image', 1, 0, 1),
(12, 'Водолазка Кусто', '12000.00', NULL, 'no_image', 0, 0, 1),
(13, 'Туфли &quot;сто лет в обед&quot;', '15000.00', NULL, 'no_image', 1, 1, 1),
(14, 'Брюки', '5700.00', NULL, 'no_image', 1, 0, 1),
(15, 'Супер-пупер вещь', '11123.90', NULL, 'no_image', 1, 1, 1),
(16, 'Копеечные ботинки1', '10.99', NULL, 'no_image', 1, 0, 1),
(33, 'СУПЕР-пупур', '1198.00', NULL, 'no_image', 1, 1, 1),
(34, 'Новый', '1600.00', NULL, 'no_image', 1, 0, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `product_category`
--

CREATE TABLE `product_category` (
  `product_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Дамп данных таблицы `product_category`
--

INSERT INTO `product_category` (`product_id`, `category_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 1),
(14, 1),
(15, 1),
(16, 1),
(33, 1),
(34, 1),
(1, 2),
(2, 2),
(5, 2),
(6, 2),
(7, 2),
(9, 2),
(10, 2),
(14, 2),
(15, 2),
(16, 2),
(33, 2),
(34, 2),
(3, 3),
(4, 3),
(5, 3),
(8, 3),
(11, 3),
(12, 3),
(14, 3),
(16, 3),
(34, 3),
(2, 4),
(4, 4),
(8, 4),
(11, 4),
(14, 4),
(16, 4),
(33, 4),
(3, 5),
(9, 5),
(10, 5),
(16, 5),
(33, 5);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(80) COLLATE utf8_bin NOT NULL,
  `password` varchar(60) COLLATE utf8_bin NOT NULL,
  `email` varchar(45) COLLATE utf8_bin NOT NULL,
  `group_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `name`, `password`, `email`, `group_id`) VALUES
(1, 'admin', '$2y$10$.DFMgNVO/Wm61wN3plZLKOAuqY2LINZ2..w79OmdRCjKrpfba.aki', 'adm@fashion.com', 1),
(2, 'operator', '$2y$10$iRXZbB1eFlIp.UNBkO/bAeIM/GL0Mdj4P1LSHWTAbS.cjlwhXrkce', 'opr@fashion.com', 2),
(3, 'Васильев Василий Васильевич', '$2y$10$5vPsb4PHwjrl1OzjQJbTVuBj4aaRXRcKmIGDESEOHozvHPhuRfuUy', 'vsy@mail.ru', 3),
(4, 'Петров Пётр Петрович', '$2y$10$j7c3tCcua/PyqxtOwh3NyOWEau394PqYIpHGveMmfv/TQuhe9ekTm', 'pty@gmail.com', 3);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `o_user_id_c_idx` (`user_id`);

--
-- Индексы таблицы `order_product`
--
ALTER TABLE `order_product`
  ADD PRIMARY KEY (`order_id`,`product_id`),
  ADD KEY `op_product_id_c_idx` (`product_id`);

--
-- Индексы таблицы `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `product_category`
--
ALTER TABLE `product_category`
  ADD PRIMARY KEY (`product_id`,`category_id`),
  ADD KEY `category_id_c_idx` (`category_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `group_id_c_idx` (`group_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `groups`
--
ALTER TABLE `groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT для таблицы `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `o_user_id_c` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Ограничения внешнего ключа таблицы `order_product`
--
ALTER TABLE `order_product`
  ADD CONSTRAINT `op_order_id_c` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `op_product_id_c` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `product_category`
--
ALTER TABLE `product_category`
  ADD CONSTRAINT `category_id_c` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pc_product_id_c` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `group_id_c` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
