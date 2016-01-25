<?php
/**
 * See class comment
 *
 * PHP Version 5
 *
 * @category   Netresearch
 * @package    DAM
 * @subpackage Hook
 * @author     Christian Opitz <christian.opitz@netresearch.de>
 * @license    http://www.netresearch.de Netresearch Copyright
 * @link       http://www.netresearch.de
 */

namespace Tx\Dam\Hook;
use Tx\Dam\Model\TxDamRecord;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectGetDataHookInterface;

/**
 * Data provider to provide txdam_* fields from current file
 * (those fields were formerly introduced by dam_ttcontent)
 *
 * @category   Netresearch
 * @package    DAM
 * @subpackage Hook
 * @author     Christian Opitz <christian.opitz@netresearch.de>
 * @license    http://www.netresearch.de Netresearch Copyright
 * @link       http://www.netresearch.de
 */
class ContentObjectGetData implements ContentObjectGetDataHookInterface
{
    /**
     * Extends the getData()-Method of \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer to process more/other commands
     *
     * @param string $getDataString Full content of getData-request e.g. "TSFE:id // field:title // field:uid
     * @param array $fields Current field-array
     * @param string $sectionValue Currently examined section value of the getData request e.g. "field:title
     * @param string $returnValue Current returnValue that was processed so far by getData
     * @param \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer $parentObject Parent content object
     * @return string Get data result
     */
    public function getDataExtension($getDataString, array $fields, $sectionValue, $returnValue, \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer &$parentObject)
    {
        $sectionValue = str_replace(' ', '', $sectionValue);
        if (substr($sectionValue, 0, 12) === 'field:txdam_') {
            $file = $parentObject->getCurrentFile();
            if ($file) {
                $key = substr($sectionValue, 12);
                $damFile = new TxDamRecord($file);
                return $damFile[$key];
            }
        }
        return $returnValue;
    }
}
?>
