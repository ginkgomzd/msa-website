
DROP TABLE IF EXISTS import_table;

CREATE TABLE `import_table` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `xml_import_timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `fetching_date` date DEFAULT NULL,
  `curation_date` date DEFAULT NULL,
  `daily_email_sent` tinyint(4) DEFAULT '0',
  `indexing_complete` tinyint(4) DEFAULT '0',
  `client_id` int(11) NOT NULL,
  `entity_type` enum('hearing','regulation','legislation') NOT NULL,
  PRIMARY KEY (`id`)
) ;