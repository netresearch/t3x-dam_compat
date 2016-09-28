<?php
/**
 * Copyright notice
 *
 * (c) 2006 Erich Bircher @ Internetgalerie <typo3@internetgalerie.ch>
 * All rights reserved
 *
 * This script is part of the TYPO3 project. The TYPO3 project is
 * free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * This copyright notice MUST APPEAR in all copies of the script!
 *
 * @category Dam
 * @package  Dam
 * @author   Axel Kummer <axel.kummer@netresearch.de>
 * @license  http://www.gnu.org/copyleft/gpl.html GPL
 * @link     http://www.netresearch.de
 */

/**
 * DAM Support
 *
 * @param array $arMarker Array filled with markers from the getItemMarkerArray function in tt_news
 *                        class. see: EXT:tt_news/pi/class.tx_ttnews.php
 * @param array $arConf   Typo3 config array.
 *
 * @return array The changed marker array
 */
function user_displayFileLinks($arMarker, $arConf)
{
    // make a reference to the parent-object
    $pObj = &$arConf['parentObj'];
    $arRow = $pObj->local_cObj->data;
    $arMarker['###FILE_LINK###'] = '';
    $arMarker['###TEXT_FILES###'] = '';

    // load TS config for newsFiles from tt_news
    $arConfNewsFiles = $pObj->conf['newsFiles.'];
    // Important: unset path
    $arConfNewsFiles['path'] = '';

    $localCobj = t3lib_div::makeInstance('tslib_cObj');

    //workspaces
    if (isset($arRow['_ORIG_uid']) && ($arRow['_ORIG_uid'] > 0)) {
        // draft workspace
        $nUid = $arRow['_ORIG_uid'];
    } else {
        // live workspace
        $nUid = $arRow['uid'];
    }
    // Check for translation
    if ($arRow['_LOCALIZED_UID']) {
        //i10n mode = mergeIfNotBlank
        if ($arRow['tx_damnews_dam_media']) {
            $nUid = $arRow['_LOCALIZED_UID'];
        }
    }

    $arDamFiles = tx_dam_db::getReferencedFiles('tt_news', $nUid, 'tx_damnews_dam_media');

    // localisation of DAM data
    while (list($key, $val) = each($arDamFiles['rows'])) {
        $arDamFiles['rows'][$key] = $GLOBALS['TSFE']->sys_page->getRecordOverlay(
            'sys_file_metadata', $val, $GLOBALS['TSFE']->sys_language_uid, ''
        );
    }

    if (is_array($arDamFiles)) {
        $arFilesStdWrap = t3lib_div::trimExplode('|', $pObj->conf['newsFiles_stdWrap.']['wrap']);
        $strFileLinks = '';

        while (list($key, $val) = each($arDamFiles['files'])) {
            if ($val) {
                $localCobj->start($arDamFiles['rows'][$key]);
                $strFileLinks .= $localCobj->filelink($val, $arConfNewsFiles);
            }
        }

        if ($strFileLinks) {
            $arMarker['###FILE_LINK###'] = $strFileLinks . $arFilesStdWrap[1];
            $arMarker['###TEXT_FILES###'] = $arFilesStdWrap[0]
                . $pObj->local_cObj->stdWrap(
                    $pObj->pi_getLL('textFiles'), $pObj->conf['newsFilesHeader_stdWrap.']
                );
        }
    }

    return $arMarker;
}
?>
