
CREATE DATABASE IF NOT EXISTS `unit_tests`;

CREATE USER IF NOT EXISTS 'unit_tests'@'localhost';

ALTER USER 'unit_tests'@'localhost' IDENTIFIED BY 'JdN-^MeE4]7|}vmq';

GRANT ALL ON `unit_tests%`.* TO 'unit_tests'@'localhost';
