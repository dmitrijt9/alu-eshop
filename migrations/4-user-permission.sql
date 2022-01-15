
INSERT INTO `resource` (`resource_id`) VALUES ('Admin:User');
INSERT INTO `resource` (`resource_id`) VALUES ('User');
INSERT INTO `permission` (`permission_id`, `role_id`, `resource_id`, `action`, `type`) VALUES (NULL, 'admin', 'Admin:User', '', 'allow');
INSERT INTO `permission` (`permission_id`, `role_id`, `resource_id`, `action`, `type`) VALUES (NULL, 'admin', 'User', '', 'allow');
