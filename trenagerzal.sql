-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Ноя 18 2026 г., 07:39
-- Версия сервера: 5.7.39-log
-- Версия PHP: 7.4.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `trenagerzal`
--

-- --------------------------------------------------------

--
-- Структура таблицы `contracts`
--

CREATE TABLE `contracts` (
  `id` int(11) NOT NULL,
  `contract_date` date NOT NULL,
  `client_id` int(11) NOT NULL,
  `trainer_id` int(11) NOT NULL,
  `workout_type_id` int(11) NOT NULL,
  `visit_days` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_workouts` int(11) NOT NULL,
  `paid_amount` decimal(10,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `contracts`
--

INSERT INTO `contracts` (`id`, `contract_date`, `client_id`, `trainer_id`, `workout_type_id`, `visit_days`, `total_workouts`, `paid_amount`) VALUES
(1, '2026-11-12', 6, 4, 1, 'ПН', 10, '0.00'),
(2, '2026-11-12', 6, 8, 5, 'Ср', 5, '0.00'),
(3, '2026-11-12', 5, 7, 4, 'Пт', 10, '0.00'),
(4, '2026-11-12', 5, 4, 2, 'Ср', 10, '10000.00'),
(5, '2026-11-12', 5, 4, 4, 'Пн', 10, '6000.00'),
(6, '2026-11-13', 9, 4, 5, 'ПН', 10, '4500.00'),
(7, '2026-11-17', 9, 8, 6, 'ПН', 16, '0.00');

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `fio` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `login` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('директор','регистратор','бухгалтер','тренер','клиент') COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `fio`, `login`, `password`, `role`, `email`, `phone`) VALUES
(1, 'Иванов Иван Иванович', '000', '000', 'директор', 'director@mail.ru', '+79991112233'),
(2, 'Петрова Анна Сергеевна', '111', '111', 'регистратор', 'registrar@mail.ru', '+79994445566'),
(3, 'Козлова Елена Васильевна', '999', '999', 'бухгалтер', 'buh@mail.ru', '+79997778899'),
(4, 'Ковалев Олег Игоревич', '888', '888', 'тренер', 'kovalev@mail.ru', '+79995554433'),
(5, 'Сидоров Алексей Петрович', '222', '222', 'клиент', 'sidorov@mail.ru', '+79001234567'),
(6, 'Кузнецова Мария Владимировна', '333', '333', 'клиент', 'kuznecova@mail.ru', '+79007654321'),
(7, 'Смирнов Дмитрий Николаевич', '777', '777', 'тренер', 'smirnov@fitness.ru', '+79992223344'),
(8, 'Волкова Екатерина Андреевна', '666', '666', 'тренер', 'volkova@fitness.ru', '+79993334455'),
(9, 'Уламаскин Павел Александрович', '555', '555', 'клиент', 'pavel459832@gmail.com', '+79023456789');

-- --------------------------------------------------------

--
-- Структура таблицы `visits`
--

CREATE TABLE `visits` (
  `id` int(11) NOT NULL,
  `contract_id` int(11) NOT NULL,
  `visit_date` date NOT NULL,
  `program` text COLLATE utf8mb4_unicode_ci,
  `attended` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `visits`
--

INSERT INTO `visits` (`id`, `contract_id`, `visit_date`, `program`, `attended`) VALUES
(1, 1, '2026-11-12', 'Разминка', 1),
(2, 4, '2026-11-12', 'Кардио тренировка', 1),
(3, 6, '2026-11-13', 'Кардио тренировка', 1),
(4, 6, '2026-11-18', 'Кардио тренировка', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `workout_types`
--

CREATE TABLE `workout_types` (
  `id` int(11) NOT NULL,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `img` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price_factor` decimal(4,2) NOT NULL DEFAULT '0.30'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `workout_types`
--

INSERT INTO `workout_types` (`id`, `title`, `price`, `img`, `price_factor`) VALUES
(1, 'Тренажерный зал', '500.00', 'img/001.PNG', '0.30'),
(2, 'Персональная тренировка', '1000.00', 'img/002.PNG', '0.30'),
(3, 'Групповая тренировка', '400.00', 'img/003.PNG', '0.30'),
(4, 'Силовая тренировка', '600.00', 'img/004.PNG', '0.30'),
(5, 'Кардио-тренировка', '450.00', 'img/002.PNG', '0.30'),
(6, 'Функциональный тренинг (Кроссфит)', '700.00', 'img/33.png', '0.30');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `contracts`
--
ALTER TABLE `contracts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_id` (`client_id`),
  ADD KEY `trainer_id` (`trainer_id`),
  ADD KEY `workout_type_id` (`workout_type_id`);

--
-- Индексы таблицы `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `login` (`login`);

--
-- Индексы таблицы `visits`
--
ALTER TABLE `visits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contract_id` (`contract_id`);

--
-- Индексы таблицы `workout_types`
--
ALTER TABLE `workout_types`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `contracts`
--
ALTER TABLE `contracts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблицы `visits`
--
ALTER TABLE `visits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `workout_types`
--
ALTER TABLE `workout_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `contracts`
--
ALTER TABLE `contracts`
  ADD CONSTRAINT `contracts_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `contracts_ibfk_2` FOREIGN KEY (`trainer_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `contracts_ibfk_3` FOREIGN KEY (`workout_type_id`) REFERENCES `workout_types` (`id`);

--
-- Ограничения внешнего ключа таблицы `visits`
--
ALTER TABLE `visits`
  ADD CONSTRAINT `visits_ibfk_1` FOREIGN KEY (`contract_id`) REFERENCES `contracts` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
