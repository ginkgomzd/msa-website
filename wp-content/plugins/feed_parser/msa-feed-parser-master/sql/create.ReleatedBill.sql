
DROP TABLE IF EXISTS related_bill;

CREATE TABLE `related_bill` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(95) DEFAULT NULL,
  `type` varchar(95) DEFAULT NULL,
  `number` varchar(95) DEFAULT NULL,
  `legislation_id` varchar(16) DEFAULT NULL COMMENT 'this is entity id profile match',
  PRIMARY KEY (`id`)
);