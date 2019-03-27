
DROP TABLE IF EXISTS prioritized_bills;

CREATE TABLE `prioritized_bills` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bill_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `entity_type` varchar(20) NOT NULL,
  `client_id` int(11) DEFAULT NULL,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique` (`bill_id`,`entity_type`,`client_id`)
);