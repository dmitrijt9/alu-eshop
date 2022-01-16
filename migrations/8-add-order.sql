SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


-- --------------------------------------------------------

CREATE TABLE `order` (
                        `order_id` int(11) NOT NULL,
                        `user_id` int(11) DEFAULT NULL,
                        `status` varchar(255) DEFAULT 'CREATED',
                        `user_name` varchar(255) DEFAULT NULL,
                        `user_address` varchar(255) DEFAULT NULL,
                        `user_email` varchar(255) DEFAULT NULL,
                        `total_price` decimal(10,2) DEFAULT NULL,
                        `last_modified` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

-- --------------------------------------------------------
CREATE TABLE `order_item` (
                             `order_item_id` int(11) NOT NULL,
                             `product_id` int(11) NOT NULL,
                             `order_id` int(11) NOT NULL,
                             `price` decimal(10,2) NOT NULL,
                             `count` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

--
-- Klíče pro exportované tabulky
--
ALTER TABLE `order`
    ADD PRIMARY KEY (`order_id`),
    ADD KEY `user_id` (`user_id`);

ALTER TABLE `order_item`
    ADD PRIMARY KEY (`order_item_id`),
    ADD UNIQUE KEY `product_id` (`product_id`,`order_id`),
    ADD KEY `order_id` (`order_id`);

--
-- AUTO_INCREMENT pro tabulky
--

--
-- AUTO_INCREMENT pro tabulku `order`
--
ALTER TABLE `order`
    MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pro tabulku `order_item`
--
ALTER TABLE `order_item`
    MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Omezení pro exportované tabulky
--

--
-- Omezení pro tabulku `order`
--
ALTER TABLE `order`
    ADD CONSTRAINT `order_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Omezení pro tabulku `order_item`
--
ALTER TABLE `order_item`
    ADD CONSTRAINT `order_item_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `order` (`order_id`) ON DELETE CASCADE ON UPDATE CASCADE,
    ADD CONSTRAINT `order_item_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE;

INSERT INTO `resource` (`resource_id`) VALUES ('Front:Order'), ('Order'), ('Admin:Order');
INSERT INTO `permission` (`permission_id`, `role_id`, `resource_id`, `action`, `type`) VALUES (NULL, 'guest', 'Front:Order', '', 'allow'), (NULL, 'authenticated', 'Front:Order', '', 'allow'), (NULL, 'admin', 'Front:Order', '', 'allow'), (NULL, 'admin', 'Order', '', 'allow'), (NULL, 'admin', 'Admin:Order', '', 'allow');
COMMIT;