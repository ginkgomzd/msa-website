
DROP TABLE IF EXISTS `profile_keyword`;

CREATE TABLE `profile_keyword`
(
  `id` int PRIMARY KEY AUTO_INCREMENT NOT NULL,
  `profile_match_id` int NOT NULL,
  `keyword` varchar(99) NOT NULL,

  UNIQUE KEY `match_keyword` (`profile_match_id`, `keyword`)
 );
