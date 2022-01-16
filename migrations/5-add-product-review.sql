
--
-- Struktura tabulky `product_review`
--

CREATE TABLE `product_review` (
                           `product_review_id` int(11) NOT NULL,
                           `product_id` smallint(5) UNSIGNED DEFAULT NULL,
                           `user_id` smallint(5) UNSIGNED DEFAULT NULL,
                           `text` text COLLATE utf8mb4_czech_ci NOT NULL,
                           `stars` int COLLATE utf8mb4_czech_ci NOT NULL,
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci COMMENT='Tabulka s recenzemi k produktum';


ALTER TABLE `product_review`
    ADD PRIMARY KEY (`product_review_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT pro tabulky
--

ALTER TABLE `product_review`
    MODIFY `product_review_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Omezení pro exportované tabulky
--

ALTER TABLE `product_review`
    ADD CONSTRAINT `product_review_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`) ON DELETE SET NULL,
    ADD CONSTRAINT `product_review_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE SET NULL;


--
-- Vložení nových záznamů pro ACL
--
INSERT INTO `resource` (`resource_id`) VALUES ('ProductReview'), ('Admin:ProductReview');
INSERT INTO `permission` (`permission_id`, `role_id`, `resource_id`, `action`, `type`) VALUES (NULL, 'admin', 'ProductReview', '', 'allow'), (NULL, 'admin', 'Admin:ProductReview', '', 'allow');

--
-- Vložení nových záznamů pro ACL
--
INSERT INTO `resource` (`resource_id`) VALUES ('Front:ProductReview');
INSERT INTO `permission` (`permission_id`, `role_id`, `resource_id`, `action`, `type`) VALUES (NULL, 'guest', 'Front:ProductReview', 'list', 'allow'), (NULL, 'authenticated', 'Front:ProductReview', 'list', 'allow');