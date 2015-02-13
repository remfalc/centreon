
ALTER TABLE `traps` ADD COLUMN `traps_downtime` enum('0','1','2') DEFAULT '0' AFTER `traps_exec_method`;
ALTER TABLE `traps` ADD COLUMN `traps_output_transform` varchar(255) DEFAULT NULL AFTER `traps_downtime`;
ALTER TABLE `traps` ADD COLUMN `traps_routing_filter_services` varchar(255) DEFAULT NULL AFTER `traps_routing_value`;
ALTER TABLE `traps` ADD COLUMN `traps_customcode` TEXT DEFAULT NULL AFTER `traps_output_transform`;

-- #4436
ALTER TABLE `extended_host_information` MODIFY ehi_notes TEXT, MODIFY ehi_notes_url TEXT, MODIFY ehi_action_url TEXT;
ALTER TABLE `extended_service_information` MODIFY esi_notes TEXT, MODIFY esi_notes_url TEXT, MODIFY esi_action_url TEXT;

ALTER TABLE auth_ressource_info CHANGE ari_value ari_value VARCHAR(1024);

-- Remove datePicker
DELETE FROM topology_JS WHERE PathName_js = "./include/common/javascript/datePicker.js";

-- Change version of Centreon
UPDATE `informations` SET `value` = '2.6.0' WHERE CONVERT( `informations`.`key` USING utf8 )  = 'version' AND CONVERT ( `informations`.`value` USING utf8 ) = '2.5.4' LIMIT 1;
