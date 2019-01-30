
DROP TABLE IF EXISTS `action_text`;

CREATE TABLE `action_text`
(
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `external_id` varchar(99) NOT NULL COMMENT 'action_text external ID',
  `original_url` longtext,
  `statetrack_url` longtext,
  `type` varchar(99) DEFAULT NULL,
  `regulation_id` int(11) NOT NULL COMMENT 'FK to regulations.id',
  PRIMARY KEY (`id`),
  UNIQUE KEY `external_id` (`external_id`)
);
