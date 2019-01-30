-- save current foreign key settings and disable foreign key checks
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
TRUNCATE `unit_tests`.`hearing`;
TRUNCATE `unit_tests`.`regulation`;
TRUNCATE `unit_tests`.`legislation`;
TRUNCATE `unit_tests`.`user_clients`;
TRUNCATE `unit_tests`.`user_settings`;
TRUNCATE `unit_tests`.`client_settings`;
TRUNCATE `unit_tests`.`user_profile`;
TRUNCATE `unit_tests`.`user_clients`;
TRUNCATE `unit_tests`.`session_info`;
TRUNCATE `unit_tests`.`related_bill`;
TRUNCATE `unit_tests`.`profile_match`;
TRUNCATE `unit_tests`.`profile_keyword`;
TRUNCATE `unit_tests`.`prioritized_bills`;
TRUNCATE `unit_tests`.`entity_text`;
TRUNCATE `unit_tests`.`action_text`;
TRUNCATE `unit_tests`.`bill_notes`;
-- reset current foreign key settings
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;