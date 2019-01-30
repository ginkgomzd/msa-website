
DROP TABLE IF EXISTS `client_settings`;

CREATE TABLE `client_settings` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL,
  `type` enum('category','state','keyword') NOT NULL,
  `category` varchar(99) NOT NULL,
  `isfrontactive` tinyint(4) DEFAULT '1',
  `ismailactive` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `setting` (`client_id`,`category`,`type`),
  CONSTRAINT `fk_csetting_client` FOREIGN KEY (`client_id`) REFERENCES `user_clients` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
);

CREATE DEFINER=`unit_tests`@`localhost` TRIGGER `unit_tests`.`client_settings_AFTER_INSERT` AFTER INSERT ON `client_settings` FOR EACH ROW
BEGIN
	INSERT INTO user_settings (user_id,type,category)
    SELECT id,NEW.type,NEW.category FROM wp_users WHERE id IN (SELECT user_id FROM wp_usermeta WHERE meta_key = 'company' AND meta_value = NEW.client_id);
END

CREATE DEFINER=`unit_tests`@`localhost` TRIGGER `unit_tests`.`client_settings_BEFORE_UPDATE` BEFORE UPDATE ON `client_settings` FOR EACH ROW
BEGIN
	IF NEW.isfrontactive = 0 THEN
	UPDATE user_settings SET isfrontactive = 0
	WHERE category = NEW.category AND type = NEW.type AND
    user_id IN (SELECT id FROM wp_users WHERE id IN (SELECT user_id FROM wp_usermeta WHERE meta_key = 'company' AND meta_value = NEW.client_id));
	END IF;
    IF NEW.ismailactive = 0 THEN
	UPDATE user_settings SET ismailactive = 0
	WHERE category = NEW.category AND type = NEW.type AND
    user_id IN (SELECT id FROM wp_users WHERE id IN (SELECT user_id FROM wp_usermeta WHERE meta_key = 'company' AND meta_value = NEW.client_id));
	END IF;
END