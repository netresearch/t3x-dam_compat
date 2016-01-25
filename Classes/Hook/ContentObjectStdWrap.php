<?php
/**
 * See class comment
 *
 * PHP Version 5
 *
 * @category   Netresearch
 * @package    ?
 * @subpackage ?
 * @author     Christian Opitz <christian.opitz@netresearch.de>
 * @license    http://www.netresearch.de Netresearch Copyright
 * @link       http://www.netresearch.de
 */

namespace Tx\Dam\Hook;
use Tx\Dam\Model\TxDamRecord;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectStdWrapHookInterface;

/**
 * Hook to override stdWrap.field in order to provide txdam_* fields from current file
 * (those fields were formerly introduced by dam_ttcontent)
 *
 * @category   Netresearch
 * @package    DAM
 * @subpackage Hook
 * @author     Christian Opitz <christian.opitz@netresearch.de>
 * @license    http://www.netresearch.de Netresearch Copyright
 * @link       http://www.netresearch.de
 */
class ContentObjectStdWrap implements ContentObjectStdWrapHookInterface
{
    /**
     * Hook for modifying $content before core's stdWrap does anything
     *
     * @param string $content Input value undergoing processing in this function. Possibly substituted by other values fetched from another source.
     * @param array $configuration TypoScript stdWrap properties
     * @param \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer $parentObject Parent content object
     * @return string Further processed $content
     */
    public function stdWrapPreProcess($content, array $configuration, \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer &$parentObject)
    {
        return $content;
    }

    /**
     * Hook for modifying $content after core's stdWrap has processed setContentToCurrent, setCurrent, lang, data, field, current, cObject, numRows, filelist and/or preUserFunc
     *
     * @param string $content Input value undergoing processing in this function. Possibly substituted by other values fetched from another source.
     * @param array $configuration TypoScript stdWrap properties
     * @param \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer $parentObject Parent content object
     * @return string Further processed $content
     */
    public function stdWrapOverride($content, array $configuration, \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer &$parentObject)
    {
        if ($configuration['field']) {
            $file = $parentObject->getCurrentFile();
            if ($file) {
                $fields = GeneralUtility::trimExplode('//', $configuration['field']);
                $damFile = null;
                foreach ($fields as $field) {
                    if (substr($field, 0, 6) === 'txdam_') {
                        $key = substr($field, 6);
                        if (!$damFile) {
                            $damFile = new TxDamRecord($file);
                        }
                        if ((string)$damFile[$key] !== '') {
                            return $damFile[$key];
                        }
                    } elseif ((string)$parentObject->data[$field] !== '') {
                        return $parentObject->data[$field];
                    }
                }
            }
        }
        return $content;
    }

    /**
     * Hook for modifying $content after core's stdWrap has processed override, preIfEmptyListNum, ifEmpty, ifBlank, listNum, trim and/or more (nested) stdWraps
     *
     * @param string $content Input value undergoing processing in this function. Possibly substituted by other values fetched from another source.
     * @param array $configuration TypoScript "stdWrap properties".
     * @param \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer $parentObject Parent content object
     * @return string Further processed $content
     */
    public function stdWrapProcess($content, array $configuration, \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer &$parentObject)
    {
        return $content;
    }

    /**
     * Hook for modifying $content after core's stdWrap has processed anything but debug
     *
     * @param string $content Input value undergoing processing in this function. Possibly substituted by other values fetched from another source.
     * @param array $configuration TypoScript stdWrap properties
     * @param \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer $parentObject Parent content object
     * @return string Further processed $content
     */
    public function stdWrapPostProcess($content, array $configuration, \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer &$parentObject)
    {
        return $content;
    }
}
?>
