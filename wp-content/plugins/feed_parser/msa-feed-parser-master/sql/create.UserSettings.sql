DROP TABLE IF EXISTS `user_settings`;

CREATE TABLE `user_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `type` varchar(20) DEFAULT NULL,
  `category` varchar(99) DEFAULT NULL,
  `isfrontactive` tinyint(4) DEFAULT '1',
  `ismailactive` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_setting_pname` (`user_id`,`category`)
);