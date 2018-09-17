
DROP TABLE IF EXISTS hearing;

CREATE TABLE `hearing`
(
  `id` int PRIMARY KEY AUTO_INCREMENT NOT NULL,
  `legislation_external_id` varchar(99) NOT NULL,
  `external_id` varchar(50) NOT NULL COMMENT 'fabricated md5 of date, time, house, committee',
  `date` date NOT NULL,
  `time` varchar(50) NOT NULL,
  `house` varchar(50),
  `committee` varchar(250),
  `place` varchar(250),

  UNIQUE KEY `leg_date_time` (`house`, `legislation_external_id`, `date`, `time`)
);
