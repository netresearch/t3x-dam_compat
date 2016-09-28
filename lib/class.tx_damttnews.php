<?php
declare(encoding = 'UTF-8');
/**
 * Hook processor.
 *
 * PHP version 5
 *
 * @category   Netresearch
 * @package    TTNews
 * @subpackage Plugin
 * @author     Alexander Opitz <alexander.opitz@netresearch.de>
 * @license    Netresearch http://www.netresearch.de/
 * @link       http://www.netresearch.de/
 */

/**
 * Hook processor class.
 *
 * This class holds the methods which are registered with tt_news hooks.
 *
 * @category   Netresearch
 * @package    TTNews
 * @subpackage Hook
 * @author     Alexander Opitz <alexander.opitz@netresearch.de>
 * @license    Netresearch http://www.netresearch.de/
 * @link       http://www.netresearch.de/
 */
class tx_damttnews
{
    /**
     * Process the ###NEWS_RSS2_ENCLOSURES### marker.
     *
     * This function is called by the Hook in the function getItemMarkerArray()
     * from class.tx_ttnews.php
     *
     * @param array  $arMarker the markerArray from the tt_news class
     * @param array  $arRow    the database row for the current news-record
     * @param array  $arConf   the TS setup array from tt_news
     *                         (holds the TS vars from the current tt_news view)
     * @param object &$pObj    reference to the parent object
     *
     * @return array $arMarker: the processed markerArray
     * @see EXT:tt_news/pi/class.tx_ttnews.php->getItemMarkerArray()
     */
    function extraItemMarkerProcessor(
        array $arMarker, array $arRow, array $arConf, &$pObj
    ) {
        // workspaces
        if (isset($arRow['_ORIG_uid']) && ($arRow['_ORIG_uid'] > 0)) {
            // draft workspace
            $uid = $arRow['_ORIG_uid'];
        } else {
            // live workspace
            $uid = $arRow['uid'];
        }
        // translations - i10n mode
        if ($arRow['_LOCALIZED_UID']) {
            //i10n mode = exclude   -> do nothing
            //i10n mode = mergeIfNotBlank
            $arConfigTtnews
                = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['tt_news']);
            if (!$confArr_ttnews['l10n_mode_imageExclude']
                && $arRow['tx_damnews_dam_images']
            ) {
                $uid = $arRow['_LOCALIZED_UID'];
            }
        }

        $arDamData = tx_dam_db::getReferencedFiles(
            'tt_news',
            $uid,
            'tx_damnews_dam_images',
            'tx_dam_mm_ref',
            '',
            array(),
            '',
            '',
            1
        );

        if (count($arDamData['files'])) {
            $strDamFile = reset($arDamData['files']);
            $arDamRow = reset($arDamData['rows']);

            $strFileURL = $pObj->config['siteUrl'] . $strDamFile;
            $nFileSize = filesize($strDamFile);
            $strFileMimeType
                = $arDamRow['file_mime_type'] . '/' . $arDamRow['file_mime_subtype'];

            $strRssEnclousre = '<enclosure url="' . $strFileURL . '" ';
            $strRssEnclousre .= 'length ="' . $nFileSize . '" ';
            $strRssEnclousre .= 'type="' . $strFileMimeType . '" />';

            $arMarker['###NEWS_RSS2_ENCLOSURES###'] = $strRssEnclousre;
        } else {
            $arMarker['###NEWS_RSS2_ENCLOSURES###'] = '';
        }

        return $arMarker;
    }
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/dam/lib/class.tx_damttnews.php']) {
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/dam/lib/class.tx_damttnews.php']);
}
