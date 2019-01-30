
DROP TABLE IF EXISTS legislation;

CREATE TABLE `legislation`
(
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `external_id` varchar(99) NOT NULL,
  `session` varchar(150) NOT NULL,
  `state` varchar(150) NOT NULL,
  `type` varchar(150) NOT NULL,
  `number` varchar(250) NOT NULL,
  `title` text NOT NULL,
  `abstract` text NOT NULL,
  `full_text_url` text NOT NULL,
  `sponsor_name` varchar(250) NOT NULL,
  `sponsor_url` text,
  `status_date` varchar(16) DEFAULT NULL,
  `status_val` varchar(150) DEFAULT NULL,
  `status_standardkey` varchar(16) DEFAULT NULL,
  `status_standard_val` varchar(150) NOT NULL,
  `status_url` varchar(150) DEFAULT NULL,
  `textUploaded` tinyint(4) DEFAULT NULL,
  `session_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `external_id` (`external_id`),
  KEY `fk_session_leg_id_idx` (`session_id`),
  CONSTRAINT `fk_session_leg_id` FOREIGN KEY (`session_id`) REFERENCES `session_info` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
 );

CREATE DEFINER=`unit_tests`@`localhost` TRIGGER `unit_tests`.`legislation_BEFORE_INSERT` BEFORE INSERT ON `legislation` FOR EACH ROW
BEGIN
	SET NEW.session_id = (SELECT id FROM session_info WHERE session_state= NEW.state AND session_year = NEW.session);
END