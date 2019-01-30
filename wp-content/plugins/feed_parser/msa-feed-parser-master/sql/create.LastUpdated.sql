
DROP TABLE IF EXISTS last_updated;

CREATE TABLE `last_updated` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `import_table_id` int(11) DEFAULT NULL,
  `document_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
);