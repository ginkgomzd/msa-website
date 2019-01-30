
DROP TABLE IF EXISTS `profile_match`;

CREATE TABLE `profile_match`
(
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `external_id` varchar(99) NOT NULL COMMENT 'profile external ID',
  `pname` varchar(99) NOT NULL COMMENT 'the parsed xml profile name',
  `entity_type` varchar(16) NOT NULL COMMENT 'matched entity type: legislation, regulation, hearing',
  `entity_id` int(11) NOT NULL COMMENT 'ID of the matched entity',
  `client_id` int(11) NOT NULL COMMENT 'client id',
  PRIMARY KEY (`id`),
  UNIQUE KEY `client_pname_bill` (`client_id`,`external_id`,`entity_type`,`entity_id`)
);
