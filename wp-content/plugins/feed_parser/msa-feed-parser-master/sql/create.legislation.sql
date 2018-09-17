
DROP TABLE IF EXISTS legislation;

CREATE TABLE `legislation`
(
  `id` int PRIMARY KEY AUTO_INCREMENT NOT NULL,
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
  UNIQUE KEY `external_id` (`external_id`)
 );
