<?php

if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}

if (!defined('PATH_txdam')) {
    define('PATH_txdam', \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY));
}
if (!defined('PATH_txdam_rel')) {
    define('PATH_txdam_rel', \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY));
}
if (!defined('PATH_txdam_siteRel')) {
    define('PATH_txdam_siteRel', \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::siteRelPath($_EXTKEY));
}

if (!defined('DAM_COMPAT')) {
    define('DAM_COMPAT', 1);
}


// that's the base API
require_once(PATH_txdam.'lib/class.tx_dam.php');

// field templates for usage in other tables to link media records
require_once(PATH_txdam.'tca_media_field.php');

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_befunc.php']['getFlexFormDSClass'][] = 'Tx\\Dam\\Hook\\FlexFormDataStructure';
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['GLOBAL']['extTablesInclusion-PostProcessing'][] = 'Tx\\Dam\\Hook\\ExtensionTables';
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tstemplate.php']['includeStaticTypoScriptSources'][] = 'Tx\\Dam\\Hook\\TsTemplate->includeStatic';

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_content.php']['getData'][] = 'Tx\\Dam\\Hook\\ContentObjectGetData';
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_content.php']['stdWrap'][] = 'Tx\\Dam\\Hook\\ContentObjectStdWrap';

$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects']['TYPO3\\CMS\\Frontend\\ContentObject\\ContentObjectRenderer'] = array(
    'className' => 'Tx\\Dam\\Xclass\\ContentObjectRenderer'
);
$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects']['tslib_cObj'] = array(
    'className' => 'Tx\\Dam\\Xclass\\ContentObjectRenderer'
);
?>
