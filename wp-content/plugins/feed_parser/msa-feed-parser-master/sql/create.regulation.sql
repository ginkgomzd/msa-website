
DROP TABLE IF EXISTS regulation;

CREATE TABLE `regulation`
(
  `id` int PRIMARY KEY AUTO_INCREMENT NOT NULL,
  `external_id` varchar(25) NOT NULL,
  `tracking_key` varchar(100),
  `state` varchar(2),
  `agency_name` varchar(250),
  `type` varchar(100),
  `state_action_type` varchar(100),
  `full_text_id` int,
  `full_text_url` varchar(999),
  `full_text_local_url` varchar(999),
  `full_text_type` text,
  `code_citation` varchar(250),
  `description` text,
  `register_date` varchar(100),
  `register_citation` varchar(100),
  `register_url` varchar(999),
  UNIQUE KEY `external_id` (`external_id`)
);
