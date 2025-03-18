-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Мар 18 2025 г., 17:18
-- Версия сервера: 8.0.34-26-beget-1-1
-- Версия PHP: 5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `m90595s5_shop_db`
--

-- --------------------------------------------------------

--
-- Структура таблицы `cart`
--
-- Создание: Мар 18 2025 г., 09:50
--

DROP TABLE IF EXISTS `cart`;
CREATE TABLE `cart` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `quantity`) VALUES
(35, 8, 7, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `delivery`
--
-- Создание: Мар 18 2025 г., 09:50
--

DROP TABLE IF EXISTS `delivery`;
CREATE TABLE `delivery` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `city` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `street` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `postal_code` varchar(20) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `delivery`
--

INSERT INTO `delivery` (`id`, `user_id`, `first_name`, `last_name`, `city`, `street`, `phone`, `postal_code`) VALUES
(3, 4, 'Кирилл', 'Головин', 'Новосибирск', 'Кутателадзе 3', '9999999999', '630060'),
(4, 6, 'Василий', 'Иванов', 'Новосибирск', 'улица Иванова, 49', '88005553535', '630117');

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--
-- Создание: Мар 18 2025 г., 09:50
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_price`, `created_at`) VALUES
(7, 6, '417598.00', '2025-03-15 12:04:54'),
(8, 6, '27397.00', '2025-03-15 12:06:41'),
(9, 6, '5000.00', '2025-03-15 12:09:56'),
(10, 6, '3000.00', '2025-03-16 07:20:47'),
(11, 4, '68298.00', '2025-03-16 11:02:00'),
(12, 4, '1299.00', '2025-03-16 11:05:37'),
(13, 4, '4599.00', '2025-03-16 11:07:34'),
(14, 4, '4599.00', '2025-03-16 11:08:32'),
(15, 4, '171999.00', '2025-03-18 09:32:59'),
(16, 4, '93998.00', '2025-03-18 14:10:21');

-- --------------------------------------------------------

--
-- Структура таблицы `order_items`
--
-- Создание: Мар 18 2025 г., 09:50
--

DROP TABLE IF EXISTS `order_items`;
CREATE TABLE `order_items` (
  `id` int NOT NULL,
  `order_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `quantity` int NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(7, 7, 10, 1, '120000.00'),
(8, 7, 2, 1, '3000.00'),
(9, 7, 8, 1, '289999.00'),
(10, 7, 19, 1, '4599.00'),
(11, 8, 13, 1, '1299.00'),
(12, 8, 17, 1, '3099.00'),
(13, 8, 3, 1, '22999.00'),
(15, 10, 2, 1, '3000.00'),
(16, 11, 4, 1, '66999.00'),
(17, 11, 13, 1, '1299.00'),
(18, 12, 13, 1, '1299.00'),
(19, 13, 19, 1, '4599.00'),
(20, 14, 19, 1, '4599.00'),
(21, 15, 28, 1, '171999.00'),
(22, 16, 32, 2, '46999.00');

-- --------------------------------------------------------

--
-- Структура таблицы `products`
--
-- Создание: Мар 18 2025 г., 09:50
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `manufacturer` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `product_type` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `stock` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `products`
--

INSERT INTO `products` (`id`, `name`, `manufacturer`, `product_type`, `description`, `price`, `image`, `stock`) VALUES
(1, 'Смартфон POCO M5 128 ГБ зеленый', 'POCO', 'Смартфон', 'ядер - 8x(2.2 ГГц), 4 ГБ, 2 SIM, IPS, 2408x1080, камера 50+2+2 Мп, NFC, 4G, GPS, FM, 5000 мА*ч', '15000.00', 'https://media1.tenor.com/m/WJ6m5nPRiIUAAAAd/poco-poco-x3-pro.gif', 10),
(2, 'БП Aerocool KCAS 1000W', 'Aerocool', 'Блок питания', '80+ Bronze, ATX 2.4, APFC, 140mm fan, CM, 24+4+4, 10xSATA, 6xPCI-E', '3000.00', 'https://media1.tenor.com/m/uEtPuBXaTGYAAAAC/power-supply-psu.gif', 10),
(3, 'Принтер лазерный HP Color LaserJet 150nw', 'HP', 'Принтер', 'цветная печать, A4, 600x600 dpi, ч/б - 18 стр/мин (A4), Ethernet (RJ-45), USB, Wi-Fi', '22999.00', 'https://media1.tenor.com/m/9crhwT3rJMwAAAAd/printer-lore.gif', 10),
(4, 'Игровая консоль Microsoft Xbox Series X', 'Microsoft', 'Игровая консоль', '1000 ГБ, с дисководом, Wi-Fi 5 (802.11ac), HDMI 2.1, до 4K UltraHD, 3840x2160', '66999.00', 'https://media1.tenor.com/m/tIFccmVtVk4AAAAC/xbox-xbox-series-x.gif', 94),
(5, 'Посудомоечная машина Indesit DFS 1A50 белый', 'Indesit', 'Посудомоечная машина', 'расход воды - 10.5 л, кол-во комплектов - 9, кол-во корзин - 2 шт, защита от протечек, 85 см х 44.8 см х 60 см', '24999.00', 'https://media1.tenor.com/m/Q7NjGwFws04AAAAd/dishwasher-cam.gif', 9),
(6, 'Холодильник с морозильником Indesit ITS 4180 W белый', 'Indesit', 'Холодильник', '298 л, внешнее покрытие-металл, размораживание - No Frost, 60 см х 185 см х 64 см', '34999.00', 'https://media.tenor.com/Fi8-KpB3WSsAAAAi/dialog-fridge.gif', 5),
(7, 'Смартфон Apple iPhone 17 Pro Max Plus черный', 'Apple', 'Смартфон', 'ядер - 99x(14.5 ГГц), 128 ГБ, 2 SIM, Extra Super Retina XDR, 2622x1206, камера 1488+1488+1337 Мп, NFC, 7G, GPS, 256 ТБ', '799999.00', 'https://media1.tenor.com/m/tuoOvXp491UAAAAC/iphone-10000.gif', 2),
(8, 'Видеокарта GeForce RTX 4090', 'Nvidia', 'Видеокарта', 'PCIe 4.0 24 ГБ GDDR6X, 384 бит, 3 x DisplayPort, HDMI, GPU 2230 МГц', '289999.00', 'https://media1.tenor.com/m/z-2KeyYKuqoAAAAC/4090-rtx-4090.gif', 9),
(9, 'Ноутбук IRBIS 15NBP3502 15.6\" серый', 'IRBIS', 'Ноутбук', 'английская/русская раскладка, 1920x1080, IPS, Intel Core i5-1155G7, ядра: 4 х 2.5 ГГц, RAM 8 ГБ, SSD 256 ГБ, Intel Iris Xe Graphics, Windows 11 Pro', '47799.00', 'https://media1.tenor.com/m/hEwfEcj2R60AAAAd/laptop-smoking.gif', 7),
(10, 'Процессор AMD FX-9999', 'AMD', 'Процессор', '10.0ГГц (Turbo-boost 20ГГц), 16Мб, DDR3-1866, Socket-AM3+, 200 ядер, 800 потоков, 0.1нм', '120000.00', 'https://media1.tenor.com/m/SPMu24VrjS0AAAAd/hot-steam.gif', 2),
(11, 'DJ пульт PIONEER DDJ-200', 'Pioneer', 'DJ пульт', 'органов управления - 43, джоги - 2 шт, пэды - 16 шт, кроссфейдер, эквалайзер, эффектор', '27499.00', 'https://media1.tenor.com/m/QEb1s62Sx64AAAAd/dj.gif', 5),
(12, 'Акустическая система Hi-Fi Radiotehnika Alfa 1.02', 'Radiotehnika', 'Акустическая система', 'фронт 2 шт, корпус - MDF, 200 Вт, 50 Гц - 20 кГц, 4Ω', '49990.00', 'https://media1.tenor.com/m/E_mxPK5WeRcAAAAC/party-mix-gachibass.gif', 12),
(13, 'Умная филаментная лампа Яндекс YNDX-00554 Filament', 'Яндекс', 'Лампа', 'Wi-Fi, E27, 7 Вт, 806 лм, 2700-6500 K, 220-240 В / 50 Гц, 1 шт', '1299.00', 'https://media1.tenor.com/m/csEmUXAV-ZkAAAAC/lamp-break.gif', 35),
(14, 'Комбинированная плита Gorenje GK5C40WF белый', 'Gorenje', 'Плита', 'конфорок газовых - 4 шт, духовка - 70 л, покрытие - эмалированная сталь, электроподжиг', '49999.00', 'https://media1.tenor.com/m/e40D0_w5usUAAAAC/rejoice.gif', 5),
(15, 'Тостер Philips HD2581/00 белый', 'Philips', 'Тостер', '830 Вт, тостов - 2, подогрев, размораживание', '1799.00', 'https://media1.tenor.com/m/2VKk3TMEvuYAAAAC/toast-fire.gif', 26),
(16, 'Микроволновая печь Samsung MG23K3515AW/BW белый', 'Samsung', 'Микроволновая печь', '23 л, 800 Вт, переключатели - кнопки, поворотный механизм, гриль, дисплей, 48.9 см x 27.5 см x 39.2 см', '12399.00', 'https://media1.tenor.com/m/6ZGsR9VD6D8AAAAd/microwave-amazed.gif', 7),
(17, 'Сетевой фильтр Ugreen 30W DigiBAR белый', 'Ugreen', 'Сетевой фильтр', 'розетки - 3, USB - 3 шт, 16 А, 4000 Вт, кабель - 2 м', '3099.00', 'https://media1.tenor.com/m/LXkqP0lOWegAAAAC/plug-wsb.gif', 14),
(18, 'Швейная машина DEXP SM-3800J', 'DEXP', 'Швейная машина', 'электромеханическая, челнок - горизонтальный, швейных операций - 38, петля - полуавтомат, расширительный столик', '4799.00', 'https://media.tenor.com/SRet1LeVEUQAAAAi/costura.gif', 9),
(19, 'Wi-Fi роутер TP-Link Archer C20', 'TP-Link', 'Роутер', '4 LAN, 100 Мбит/с, 4 (802.11n), 5 (802.11ac), IPv6', '4599.00', 'https://media1.tenor.com/m/fv1_Aur3hqoAAAAd/how-to-basic-how-to-basic-egg.gif', 37),
(20, 'Электросамокат NineBot KickScooter F2 Pro черный', 'NineBot', 'Электросамокат', 'диаметр колес 10\", 10\", до 120 кг, 25 км/ч, 12800 мА*ч, до 55 км', '64999.00', 'https://media1.tenor.com/m/h58QNilDrqoAAAAd/on-my-way-couch-scooter.gif', 21),
(21, 'БП GIGABYTE GP-P750GM черный', 'GIGABYTE', 'Блок питания', '750 Вт, 80+ Gold, APFC, 20+4 pin, 2 x 4+4 pin CPU, 8 SATA, 4 x 6+2 pin PCI-E', '9299.00', 'https://media1.tenor.com/m/ngpGMNNjAFIAAAAd/gigabyte-psu.gif', 16),
(22, 'БП be quiet! SYSTEM POWER 10 550W черный', 'be quiet!', 'Блок питания', '550 Вт, 80+ Bronze, APFC, 20+4 pin, 4+4 pin, 4 pin CPU, 6 SATA, 2 x 6+2 pin PCI-E', '7999.00', 'https://media1.tenor.com/m/UkiWpbV1LBMAAAAd/gamersnexus-gigabyte.gif', 27),
(23, 'Смартфон Samsung SM-N930F Galaxy Note 7', 'Samsung', 'Смартфон', '5.7\" 64Gb Silver 8(4х2.3+4х1.6ГГц), 4096Мб, 2560x1440, LTE, GPS, Cam12, 3500mAh, And6.0', '5000.00', 'https://c.tenor.com/c3AUpAuYUXsAAAAd/tenor.gif', 3),
(24, 'Компьютерное кресло Aerocool AC110 Red красный', 'Aerocool', 'Компьютерное кресло', 'экокожа, до 125 кг, подголовник, подлокотники - нерегулируемые', '14300.00', 'https://media1.tenor.com/m/9OoAmhPHsl4AAAAd/gaming-racing.gif', 19),
(25, 'Смартфон Nokia X10 6/128Gb', 'Nokia', 'Смартфон', 'ядер - 8x(2 ГГц), 6 ГБ, 2 SIM, IPS, 2400x1080, камера 48+5+5+2 Мп, NFC, 5G, GPS, FM, 4470 мА*ч', '15000.00', 'https://media1.tenor.com/m/FodmkwCJFrgAAAAC/chicken-nokia.gif', 7),
(26, '7.6\" Смартфон Samsung Galaxy Z Fold6 256 ГБ черный', 'Samsung', 'Смартфон', 'ядер - 8x(3.39 ГГц), 12 ГБ, 2 SIM, Dynamic AMOLED 2X, 2160x1856, камера 50+12+10 Мп, NFC, 5G, GPS, 4400 мА*ч', '159999.00', 'https://media1.tenor.com/m/rJ0WtcguLtwAAAAd/samsung-apple.gif', 11),
(27, '15.6\" Ноутбук Dell Vostro 3520 черный', 'Dell', 'Ноутбук', 'английская/русская раскладка, 1920x1080, WVA (TN+film), Intel Core i3-1215U, ядра: 2 + 4 х 1.2 ГГц + 0.9 ГГц, RAM 8 ГБ, SSD 512 ГБ, Intel UHD Graphics, без ОС', '42999.00', 'https://media1.tenor.com/m/xEiTVCSYqc4AAAAd/rage-smash.gif', 14),
(28, '14\" Ноутбук Dell Latitude 5440 черный', 'Dell', 'Ноутбук', 'английская/русская раскладка, 1920x1080, IPS, Intel Core i5-1335U, ядра: 2 + 8 х 1.3 ГГц + 0.9 ГГц, RAM 16 ГБ, SSD 512 ГБ, Intel Iris Xe Graphics, Windows 11 Pro', '171999.00', 'https://media1.tenor.com/m/eB452JUFvA8AAAAd/laptop-repair-laptop-repair-in-woking.gif', 17),
(29, '15.6\" Ноутбук Lenovo Legion 5 15ACH6A белый', 'Lenovo', 'Ноутбук', 'английская/русская раскладка, 1920x1080, IPS, AMD Ryzen 5 5600H, ядра: 6 х 3.3 ГГц, RAM 16 ГБ, SSD 1000 ГБ, Radeon RX 6600M 8 ГБ, Windows 10 Home Single Language', '41600.00', 'https://media1.tenor.com/m/jPLST4ri1IEAAAAd/laptop-smoking-smoking-laptop.gif', 26),
(30, 'Процессор Intel Core i5-12400 OEM', 'Intel', 'Процессор', 'LGA 1700, 6 x 2.5 ГГц, L2 - 7.5 МБ, L3 - 18 МБ, 2 х DDR4, DDR5-4800 МГц, Intel UHD Graphics 730, TDP 117 Вт', '13799.00', 'https://media1.tenor.com/m/FmXs1N-CpCgAAAAd/cpu-cpu-kill.gif', 51),
(31, 'Видеокарта AMD Radeon RX 7800 XT NITRO+', 'AMD', 'Видеокарта', 'PCIe 4.0 16 ГБ GDDR6, 256 бит, 2 x DisplayPort, 2 x HDMI', '69999.00', 'https://media1.tenor.com/m/ctTMSQ2hKK8AAAAC/amd-nvidia.gif', 20),
(32, '6.74\" Смартфон OnePlus Nord 3 256 ГБ зеленый', 'OnePlus', 'Смартфон', 'ядер - 8x(3.05 ГГц), 16 ГБ, 2 SIM, Fluid AMOLED, 2772x1240, камера 50+8+2 Мп, NFC, 5G, GPS, 5000 мА*ч', '46999.00', 'https://media1.tenor.com/m/PkxCP5PwgtsAAAAd/nord-3-oneplus-nord.gif', 78);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--
-- Создание: Мар 18 2025 г., 09:50
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `password` text COLLATE utf8mb4_general_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `is_admin` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `is_admin`) VALUES
(4, 'googlflex', 'qwerty@gmail.com', '$2y$10$RHnsgRnkEvNy4H82BDDtZusJJZFKpBBVLRuNzWJHjupKJHl0QqtvK', NULL, 1),
(5, 'Любовь', 'liubovsheyda@gmail.com', '$2y$10$ZST5StdUg96LTnGBhgWLIeaG75pUNUi0gt0rRg5TaCljXus.mc/Y.', NULL, 1),
(6, 'Васек', 'vasek@gmail.com', '$2y$10$frvsg0L/TMTJEhbuYyfwOeZKUsbnOjdjPWA/QyIN9ySZX3nSGgjlS', NULL, 0),
(7, 'FlatCubAccountant', 'pavel.gorizon@yandex.ru', '$2y$10$CaoyT.zOE9AeUTryPgM21u19ktXeCc8o9j6FKw1CVvtmfr1ln7OLm', NULL, 0),
(8, 'fedor', 'mikhailkireev1703@gmail.com', '$2y$10$mVmoXSBGnjH3CodaCaT6WOxd0bzGFB0Mu9VVlce.kGkkUnnMUY1Oa', NULL, 0);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `delivery`
--
ALTER TABLE `delivery`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Индексы таблицы `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT для таблицы `delivery`
--
ALTER TABLE `delivery`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT для таблицы `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT для таблицы `products`
--
ALTER TABLE `products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `delivery`
--
ALTER TABLE `delivery`
  ADD CONSTRAINT `delivery_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ограничения внешнего ключа таблицы `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
