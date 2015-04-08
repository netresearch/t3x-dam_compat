<?php
declare (encoding = 'UTF-8');
/**
 * DAM guifunc replace
 *
 * PHP version 5
 *
 * @category   Netresearch
 * @package    DAM
 * @subpackage GUIfunc
 * @author     Thomas Schöne <thomas.schoene@netresearch.de>
 * @license    http://www.netresearch.de Netresearch Copyright
 * @link       http://www.netresearch.de
 */

/**
 * DAM guifunc replace
 *
 * PHP version 5
 *
 * @category   Netresearch
 * @package    DAM
 * @subpackage GUIfunc
 * @author     Thomas Schöne <thomas.schoene@netresearch.de>
 * @license    http://www.netresearch.de Netresearch Copyright
 * @link       http://www.netresearch.de
 */
class tx_dam_guifunc
{
    /**
     * Returns a media type icon from a record
     * 
     * @param array $infoArr Record array
     * 
     * @deprecated use \TYPO3\CMS\Backend\Utility\IconUtility::getSpriteIconForFile
     * @return string          Rendered icon
     */
    function getMediaTypeIconBox($infoArr)
    {
        return \TYPO3\CMS\Backend\Utility\IconUtility::
            getSpriteIconForFile('jpeg');

    }
}

?>
