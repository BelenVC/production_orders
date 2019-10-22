<?php

$sql = array();

$sql[] = 'DROP TABLE IF EXISTS `'._DB_PREFIX_.'factory_section`';
$sql[] = 'DROP TABLE IF EXISTS `'._DB_PREFIX_.'factory_state`';
$sql[] = 'DROP TABLE IF EXISTS `'._DB_PREFIX_.'factory_state_order`';

foreach ($sql as $query)
{
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}

?>