
DROP TABLE IF EXISTS regulation;

CREATE TABLE `regulation`
(
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `external_id` varchar(25) NOT NULL,
  `tracking_key` varchar(100) DEFAULT NULL,
  `state` varchar(2) DEFAULT NULL,
  `agency_name` varchar(250) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `state_action_type` varchar(100) DEFAULT NULL,
  `full_text_id` int(11) DEFAULT NULL,
  `full_text_url` varchar(999) DEFAULT NULL,
  `full_text_local_url` varchar(999) DEFAULT NULL,
  `full_text_type` text,
  `code_citation` varchar(250) DEFAULT NULL,
  `description` text,
  `register_date` varchar(100) DEFAULT NULL,
  `register_citation` varchar(100) DEFAULT NULL,
  `register_url` varchar(999) DEFAULT NULL,
  `textUploaded` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `external_id` (`external_id`)
);
