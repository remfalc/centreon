-- Get globals informations from Centreon in Centreon Broker configuration
UPDATE cb_field SET external = 'T=options:C=value:CK=key:K=interval_length' WHERE cb_field_id = 16;
UPDATE cb_field SET external = 'D=centreon_storage:T=config:C=RRDdatabase_path:CK=id:K=1' WHERE cb_field_id = 13;
UPDATE cb_field SET external = 'D=centreon_storage:T=config:C=RRDdatabase_status_path:CK=id:K=1' WHERE cb_field_id = 14;
UPDATE cb_field SET external = 'D=centreon_storage:T=config:C=len_storage_rrd:CK=id:K=1' WHERE cb_field_id = 17;

-- Add informations for rrdcached in Centreon Broker
INSERT INTO cb_field (cb_field_id, fieldname, displayname, description, fieldtype, external) VALUES
(36, 'path', 'Unix socket', 'The Unix socket to use to communicate with rrdcached', 'text', 'T=options:C=value:CK=key:K=rrdcached_unix_path'),
(37, 'port', 'TCP port', 'The port od TCP socket to use to communicate with rrdcached', 'int', 'T=options:C=value:CK=key:K=rrdcached_port');
DELETE FROM cb_type_field_relation WHERE cb_type_id = 13 AND is_required = 0 AND cb_field_id IN (1, 11);
INSERT INTO cb_type_field_relation (cb_type_id, cb_field_id, is_required, order_display) VALUES
(13, 36, 0, 3),
(13, 37, 0, 4);

UPDATE `informations` SET `value` = '2.4.0-RC6' WHERE CONVERT( `informations`.`key` USING utf8 )  = 'version' AND CONVERT ( `informations`.`value` USING utf8 ) = '2.4.0-RC5' LIMIT 1;

ALTER TABLE `giv_components_template` ADD `ds_total` ENUM('0', '1') DEFAULT '0' AFTER `ds_last`;