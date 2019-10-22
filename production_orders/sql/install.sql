CREATE TABLE IF NOT EXISTS `PREFIX_factory_section` (
`id_factory_section` int(10) NOT NULL AUTO_INCREMENT,
`factory_section` varchar(64),
PRIMARY KEY (`id_sfactory_section`)
) ENGINE=ENGINE_TYPE DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `PREFIX_factory_states` (
`id_factory_state` int(10) NOT NULL AUTO_INCREMENT,
`factory_state` varchar(64),
PRIMARY KEY (`id_factory_state`)
) ENGINE=ENGINE_TYPE DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `PREFIX_factory_state_orders` (
`id_factory_state_order` int(10) NOT NULL AUTO_INCREMENT,
`id_factory_state` int(10) NOT NULL,
`id_order` int(10) NOT NULL,
`id_employee`  int(10) NOT NULL,
`date_upd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
PRIMARY KEY (`id_seur_services_type`)
) ENGINE=ENGINE_TYPE DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
