
DROP TABLE IF EXISTS session_info;

CREATE TABLE `session_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `session_name` varchar(99) DEFAULT NULL,
  `session_info` text,
  `session_state` varchar(2) DEFAULT NULL,
  `is_active` tinyint(4) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `session_year` varchar(45) DEFAULT NULL,
  `prefiling` varchar(45) DEFAULT NULL,
  `convene_date` varchar(45) DEFAULT NULL,
  `adjourn_date` varchar(45) DEFAULT NULL,
  `carryover` tinyint(4) DEFAULT '0',
  `additional_info` text,
  PRIMARY KEY (`id`)
);