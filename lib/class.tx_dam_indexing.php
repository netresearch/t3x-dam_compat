<?php
/**
 * DAM indexing replace
 *
 * PHP version 5
 *
 * @category   Netresearch
 * @package    DAM
 * @subpackage Indexing
 * @author     Thomas Schöne <thomas.schoene@netresearch.de>
 * @license    http://www.netresearch.de Netresearch Copyright
 * @link       http://www.netresearch.de
 */

/**
 * DAM indexing replace
 *
 * PHP version 5
 *
 * @category   Netresearch
 * @package    DAM
 * @subpackage Indexing
 * @author     Thomas Schöne <thomas.schoene@netresearch.de>
 * @license    http://www.netresearch.de Netresearch Copyright
 * @link       http://www.netresearch.de
 */
class tx_dam_indexing
{

    /**
     * Converts a PHP array into an XML string.
     *
     * The XML output is optimized for readability since associative keys are
     * used as tagnames.
     * This also means that only alphanumeric characters are allowed in the
     * tag names AND only keys NOT starting with numbers
     * (so watch your usage of keys!).
     * However there are options you can set to avoid this problem.
     * Numeric keys are stored with the default tagname "numIndex" but can be
     * overridden to other formats)
     * The function handles input values from the PHP array in a binary-safe way;
     * All characters below 32 (except 9,10,13) will trigger the content to be
     * converted to a base64-string
     * The PHP variable type of the data IS preserved as long as the types are
     * strings, arrays, integers and booleans. Strings are the default
     * type unless the "type" attribute is set.
     *
     * @param array $array The input PHP array with any kind of data;
     *
     * @return string An XML string made from the input content in the array.
     *
     * @see xml2array()
     * @deprecated Use \TYPO3\CMS\Core\Utility\GeneralUtility::array2xml($array);
     */
    function array2xml($array)
    {
        return \TYPO3\CMS\Core\Utility\GeneralUtility::array2xml($array);
    }

    /**
     * Writes an entry in the logfile
     *
     * @param integer $indexRun  The time stamp of the index run
     * @param string  $type      man(ual), auto, cron
     * @param string  $message   short description
     * @param integer $itemCount number of elements indexed (is 1 for error entry)
     * @param integer $error     flag. 0 = message
     *                           1 = error (user problem)
     *                           2 = System Error (which should not happen)
     *
     * @return integer		 uid of the inserted log entry
     */
    function writeLog($indexRun, $type, $message, $itemCount, $error)
    {

    }

}

 ?>
