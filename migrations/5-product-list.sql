
INSERT INTO `resource` (`resource_id`) VALUES ('Front:Product');
INSERT INTO permission (role_id, resource_id, action, type) VALUES ('guest', 'Front:Product', '', 'allow')
INSERT INTO permission (role_id, resource_id, action, type) VALUES ('authenticated', 'Front:Product', '', 'allow')
INSERT INTO permission (role_id, resource_id, action, type) VALUES ('admin', 'Front:Product', '', 'allow')
