
DROP TABLE IF EXISTS `profile_keyword`;

CREATE TABLE `profile_keyword`
(
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `profile_match_id` int(11) NOT NULL,
  `keyword` varchar(99) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `match_keyword` (`profile_match_id`,`keyword`),
  CONSTRAINT `fk_profile_match` FOREIGN KEY (`profile_match_id`) REFERENCES `profile_match` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
 );
