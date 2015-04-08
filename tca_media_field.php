<?php

/**
 * Get TCA array for media fields/config
 *
 * @param string $type	   Name of the predefined TCA definition.
 * @param string $MM_ident Ident string for MM relations. Has to be set for field
 *                         definitions that uses MM relations.
 * 
 * @deprecated Use \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig()
 * 
 * @return array
 */
function txdam_getMediaTCA($type, $MM_ident='')
{
    list($configType, $configPart) = explode('_', $type, 2);
    if ($configType == 'image' || $configType == 'media') {
        $lllKey = $configType . ($configType != 'media' ? 's' : '');
        $column = array(
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.' . $lllKey,
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                $MM_ident,
                array(
                    'appearance' => array(
                        'createNewRelationLinkTitle' => 'LLL:EXT:cms/locallang_ttc.xlf:' . $lllKey . '.addFileReference'
                    )
                ),
                ($configType == 'image') ? $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'] : ''
            )
        );
    } else {
        \TYPO3\CMS\Core\Utility\GeneralUtility::deprecationLog(
            "txdam_getMediaTCA('$type', '$MM_ident') is deprecated"
        );
        return array();
    }
    return $configPart == 'config' ? $column['config'] : $column;
}

/**
 * Adds an entry to the "ds" array of the tt_content field "ce_flexform".
 *
 * @todo Actually implement this method
 * @param string $piKeyToMatch The same value as the key for the plugin
 * @param string $value        Either a reference to a flex-form XML file (eg.
 *                             "FILE:EXT:newloginbox/flexform_ds.xml") or the XML
 *                             directly.
 * @param string $field        The tt_content field (default is "ce_flexform")
 *
 * @deprecated
 *
 * @return void
 */
function txdam_addCTypeFlexFormValue($piKeyToMatch, $value, $field='ce_flexform')
{
}
?>
