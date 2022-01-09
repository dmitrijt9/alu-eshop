CREATE TABLE `wheel_size` (
                            `wheel_size_id` smallint(5) UNSIGNED NOT NULL,
                            `size` varchar(100) COLLATE utf8mb4_czech_ci NOT NULL,
                            `description` varchar(300) COLLATE utf8mb4_czech_ci NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci COMMENT='Velikost kola';

CREATE TABLE `wheel_color` (
                              `wheel_color_id` smallint(5) UNSIGNED NOT NULL,
                              `color` varchar(100) COLLATE utf8mb4_czech_ci NOT NULL,
                              `description` varchar(300) COLLATE utf8mb4_czech_ci NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci COMMENT='Barva kola';

ALTER TABLE `wheel_size`
    ADD PRIMARY KEY (`wheel_size_id`);

ALTER TABLE `wheel_size`
    MODIFY `wheel_size_id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `wheel_color`
    ADD PRIMARY KEY (`wheel_color_id`);

ALTER TABLE `wheel_color`
    MODIFY `wheel_color_id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT;


ALTER TABLE `product`
   ADD COLUMN `wheel_size_id` smallint(5) UNSIGNED DEFAULT NULL,
   ADD COLUMN `wheel_color_id` smallint(5) UNSIGNED DEFAULT NULL;
--
-- Klíče pro tabulku `product`
--
ALTER TABLE `product`
  ADD KEY `wheel_size_id` (`wheel_size_id`),
  ADD KEY `wheel_color_id` (`wheel_color_id`);

--
-- Vložení nových záznamů pro ACL
--
INSERT INTO `resource` (`resource_id`) VALUES ('WheelSize'), ('Admin:WheelSize');
INSERT INTO `resource` (`resource_id`) VALUES ('WheelColor'), ('Admin:WheelColor');
INSERT INTO `permission` (`permission_id`, `role_id`, `resource_id`, `action`, `type`) VALUES (NULL, 'admin', 'WheelSize', '', 'allow'), (NULL, 'admin', 'Admin:WheelSize', '', 'allow'),(NULL, 'admin', 'WheelColor', '', 'allow'), (NULL, 'admin', 'Admin:WheelColor', '', 'allow');

--
-- Vložení nových záznamů pro ACL
--
INSERT INTO `resource` (`resource_id`) VALUES ('Front:WheelSize');
INSERT INTO `resource` (`resource_id`) VALUES ('Front:WheelColor');
INSERT INTO `permission` (`permission_id`, `role_id`, `resource_id`, `action`, `type`) VALUES (NULL, 'guest', 'Front:WheelSize', 'list', 'allow'), (NULL, 'guest', 'Front:WheelSize', 'show', 'allow'), (NULL, 'authenticated', 'Front:WheelSize', 'list', 'allow'), (NULL, 'authenticated', 'Front:WheelSize', 'show', 'allow'), (NULL, 'guest', 'Front:WheelColor', 'list', 'allow'), (NULL, 'guest', 'Front:WheelColor', 'show', 'allow'), (NULL, 'authenticated', 'Front:WheelColor', 'list', 'allow'), (NULL, 'authenticated', 'Front:WheelColor', 'show', 'allow');