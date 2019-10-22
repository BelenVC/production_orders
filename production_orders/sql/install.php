<?php

$sql = array();

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'factory_section` (
`id_factory_section` int(10) NOT NULL AUTO_INCREMENT,
`factory_section` varchar(64),
PRIMARY KEY (`id_sfactory_section`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 AUTO_INCREMENT=1';

$sql[] = 'INSERT INTO `'._DB_PREFIX_.'factory_section` (`id_factory_section`, `factory_section`, `deleted`) VALUES
(1, "Espuma", 0),
(2, "Tapizado", 0),
(3, "Soldadura", 0),
(4, "Corte Sofás", 0),
(5, "Corte Funda", 0),
(6, "Costura", 0),
(7, "Pegado", 0),
(8, "Express y Vencimientos", 0),
(9, "Sin Express y Sin Vencimientos", 0)
(10, "Expediciones", 0);';

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'factory_states` (
`id_factory_state` int(10) NOT NULL AUTO_INCREMENT,
`factory_state` varchar(64),
PRIMARY KEY (`id_factory_state`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 AUTO_INCREMENT=1';

$sql[] = 'INSERT INTO `'._DB_PREFIX_.'factory_states` (`id_factory_estate`, `factory_state`, `id_order_state`) VALUES
(1, "CortadoEspuma", 207),
(2, "Cortado Tapizado", 29),
(3, "Pegado", 299),
(4, "Cortado Funda", 276),
(5, "Cortado Sofás", 17),
(6, "Cosido Sofás", 18),
(7, "Cortado y Cosido Colchones", 49),
(8, "Soldadura", 296);';

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'factory_state_orders` (
`id_factory_state_order` int(10) NOT NULL AUTO_INCREMENT,
`id_factory_state` int(10) NOT NULL,
`id_order` int(10) NOT NULL,
`id_employee`  int(10) NOT NULL,
`date_upd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
PRIMARY KEY (`id_seur_services_type`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 AUTO_INCREMENT=1';


foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}

?>