<?php
$EM_CONF[$_EXTKEY] = array(
	'title' => 'DAM compatibility layer',
	'description' => 'Extension for TYPO3 6.2+ which provides important DAM API methods as Facades utilizing the FAL. Should be installed into typo3conf/ext/dam in order to seamlessly replace DAM and allow other extensions and templates to further require the extension, files and methods.',
	'category' => 'services',
	'shy' => 0,
	'version' => '1.3.3', // Latest DAM version
	'dependencies' => '',
	'conflicts' => '',
	'suggests' => '',
	'priority' => '',
	'loadOrder' => '',
	'module' => '',
	'state' => 'stable',
	'uploadfolder' => 1,
	'createDirs' => '',
	'modify_tables' => '',
	'clearcacheonload' => 0,
	'lockType' => '',
	'author' => 'Christian Opitz',
	'author_email' => 'christian.opitz@netresearch.de',
	'author_company' => 'Netresearch GmbH & Co. KG',
	'CGLcompliance' => '',
	'CGLcompliance_note' => '',
	'constraints' => array(
		'depends' => array(
			'typo3' => '6.2.0-0.0.0',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
);
?>