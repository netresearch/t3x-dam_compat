<?php

include('class.tx_dam_db.php');

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

class tx_dam_tceFunc
{
    /**
     * Throw an exception when this method is called, as this shouldn't happen
     * anymore because the group fields with tx_dam relation must be sorted out
     * before by some hooks (e.g. {@see Tx\Dam\Hook\FlexFormDataStructure})
     * 
     * This is necessary because at this point it's indeed possible to change the
     * output of the field but not the record retrieval and storage and so TYPO3
     * would further read and write from/to tx_dam_mm_ref
     *
     * @deprecated Use ExtensionManagementUtility::getFileFieldTCAConfig()
     * 
     * @param array  &$PA    An array with additional configuration options.
     * @param object &$fobj TCEForms object reference
     * 
     * @return string	    The HTML code for the TCEform field
     */
    function getSingleField_typeMedia(&$PA, &$fObj)
    {
        throw new \Tx\Dam\Exception\UnexcpectedMethodCallException(
            'The configurations of fields that use this method are expected to be '
            . 'mapped to their FAL counterparts'
        );
    }

    /**
     * Throw an Exception when this method is called, as this shouldn't happen 
     * anymore, because the flexform don't need this userfunc anymore to render 
     * a treeview
     *
     * @param array  $PA   An array with additional configuration options.
     * @param object $fobj TCEForms object reference
     *
     * @return string	   The HTML code for the TCEform field
     */
    function getSingleField_selectTree($PA, &$fObj)	{
        throw new \Tx\Dam\Exception\UnexcpectedMethodCallException(
            'Fields that need to render a select tree doesn`t need this method '
            . 'anymore. So it is depricated.');
    }
}
