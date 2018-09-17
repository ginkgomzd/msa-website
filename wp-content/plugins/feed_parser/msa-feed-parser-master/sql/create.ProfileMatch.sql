
DROP TABLE IF EXISTS `profile_match`;

CREATE TABLE `profile_match`
(
  `id` int PRIMARY KEY AUTO_INCREMENT NOT NULL,
  `external_id` varchar(99) NOT NULL COMMENT 'profile external ID',
  `pname` varchar(99) NOT NULL COMMENT 'the parsed xml profile name',
  `entity_type` varchar(16) NOT NULL COMMENT 'matched entity type: legislation, regulation, hearing',
  `entity_id` int NOT NULL COMMENT 'ID of the matched entity',
  `client` varchar(99) NOT NULL COMMENT 'client name',

  UNIQUE KEY `client_pname_bill` (`client`, `external_id`, `entity_type`, `entity_id`)
);
