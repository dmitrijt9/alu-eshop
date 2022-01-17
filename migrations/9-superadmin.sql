INSERT INTO role (role_id) VALUES ('superadmin')

INSERT INTO `permission` (`permission_id`, `role_id`, `resource_id`, `action`, `type`) VALUES
    (NULL, 'superadmin', 'Admin:Category', '', 'allow'),
    (NULL, 'superadmin', 'Admin:Dashboard', '', 'allow'),
    (NULL, 'superadmin', 'Category', '', 'allow'),
    (NULL, 'superadmin', 'Product', '', 'allow'),
    (NULL, 'superadmin', 'Admin:Product', '', 'allow'),
    (NULL, 'superadmin', 'WheelSize', '', 'allow'),
    (NULL, 'superadmin', 'Admin:WheelSize', '', 'allow'),
    (NULL, 'superadmin', 'WheelColor', '', 'allow'),
    (NULL, 'superadmin', 'Admin:WheelColor', '', 'allow'),
    (NULL, 'superadmin', 'Admin:User', '', 'allow'),
    (NULL, 'superadmin', 'User', '', 'allow'),
    (NULL, 'superadmin', 'Front:Product', '', 'allow'),
    (NULL, 'superadmin', 'ProductReview', '', 'allow'),
    (NULL, 'superadmin', 'Admin:ProductReview', '', 'allow'),
    (NULL, 'superadmin', 'Front:Cart', '', 'allow'),
    (NULL, 'superadmin', 'Order', '', 'allow'),
    (NULL, 'superadmin', 'Admin:Order', '', 'allow');

DELETE * FROM `permission` WHERE `role_id`='admin' AND `resource_id`='Admin:User'




