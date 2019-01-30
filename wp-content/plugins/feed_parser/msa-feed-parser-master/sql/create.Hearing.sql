
DROP TABLE IF EXISTS hearing;

CREATE TABLE `hearing`
(
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `legislation_external_id` varchar(99) NOT NULL,
  `external_id` varchar(50) NOT NULL COMMENT 'fabricated md5 of date, time, house, committee',
  `date` date NOT NULL,
  `time` varchar(50) NOT NULL,
  `house` varchar(50) DEFAULT NULL,
  `committee` varchar(250) DEFAULT NULL,
  `place` varchar(250) DEFAULT NULL,
  `state` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `leg_date_time` (`house`,`legislation_external_id`,`date`,`time`,`external_id`)
);
