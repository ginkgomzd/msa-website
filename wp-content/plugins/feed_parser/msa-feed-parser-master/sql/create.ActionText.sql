
DROP TABLE IF EXISTS `action_text`;

CREATE TABLE `action_text`
(
  `id` int PRIMARY KEY AUTO_INCREMENT NOT NULL,
  `external_id` varchar(99) NOT NULL COMMENT 'action_text external ID',
  `original_url` varchar(255),
  `statetrack_url` varchar(255),
  `type` varchar(99),
  `regulation_id` int NOT NULL COMMENT 'FK to regulations.id',
  UNIQUE KEY `external_id` (`external_id`)
);
