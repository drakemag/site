ALTER TABLE  `#__sptransfer_tables` ADD UNIQUE (
`extension_name` ,
`name`
);
ALTER TABLE `#__sptransfer_tables` AUTO_INCREMENT=1000;