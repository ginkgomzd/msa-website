
DROP TABLE IF EXISTS user_clients;

CREATE TABLE `user_clients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client` varchar(95) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `client_UNIQUE` (`client`)
);