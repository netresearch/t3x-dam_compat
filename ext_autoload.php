<?php

/**
 * Extension autoloader.
 *
 * PHP version 5
 *
 * @category Netresearch
 * @package  DAM
 * @author   Christian Opitz <christian.opitz@netresearch.de>
 * @license  http://www.netresearch.de Netresearch
 * @link     http://www.netresearch.de
 */

$extPath = t3lib_extMgm::extPath('dam');
$paths = array();
foreach (array(
    'tx_dam_db',
    'tx_dam_tsfe',
    'tx_damtvc_tsfe',
    'tx_dam_media',
    'tx_damttnews',
    ) as $class) {
    $paths[$class] = $extPath . 'lib/class.' . $class . '.php';
}
return $paths;
?>
