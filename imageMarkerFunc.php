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
 * @param array $arParams $markerArray and $config of the current news item in an array
 * @param array $arConf   Typo3 config array.
 *
 * @return array The processed markerArray
 */
function user_imageMarkerFunc($arParams, $arConf)
{
    $arMarker = $arParams[0];
    $arImageConf = $arParams[1];
    // make a reference to the parent-object
    $pObj = &$arConf['parentObj'];
    $arRow = $pObj->local_cObj->data;

    $strMode = $GLOBALS['TSFE']->tmpl->setup['plugin.']['dam_ttnews.']['mode'];

    $nImageNum = isset($arImageConf['imageCount']) ? $arImageConf['imageCount'] : 1;
    $nImageNum = t3lib_utility_Math::forceIntegerInRange($nImageNum, 0, 100);
    $strImgCode = '';

    $arImageCaptions = explode(chr(10), $arRow['imagecaption']);
    $arImageAltTexts = explode(chr(10), $arRow['imagealttext']);
    $arImageTitleTexts = explode(chr(10), $arRow['imagetitletext']);

    // to get correct DAM files, set uid

    // workspaces
    if (isset($arRow['_ORIG_uid']) && ($arRow['_ORIG_uid'] > 0)) {
        // draft workspace
        $nUid = $arRow['_ORIG_uid'];
    } else {
        // live workspace
        $nUid = $arRow['uid'];
    }

    // translations - i10n mode
    if ($arRow['_LOCALIZED_UID']) {
        //i10n mode = exclude   -> do nothing
        //i10n mode = mergeIfNotBlank
        $arConfTtnews = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['tt_news']);

        if (!$arConfTtnews['l10n_mode_imageExclude']) {
            if ($arRow['tx_damnews_dam_images']) $nUid = $arRow['_LOCALIZED_UID'];
        }
    }

    $nCc = 0;
    $bShift = false;

    // Get DAM data
    $strInfoFields = tx_dam_db::getMetaInfoFieldList(
        true, array('alt_text' => 'alt_text', 'caption' => 'caption')
    );
    $arDamData = tx_dam_db::getReferencedFiles(
        'tt_news', $nUid, 'tx_damnews_dam_images', 'tx_dam_mm_ref', $strInfoFields
    );
    $arDamFiles = $arDamData['files'];
    $arDamRows = $arDamData['rows'];

    // Localisation of DAM data
    while (list($key, $val) = each($arDamRows)) {
        $arDamRows[$key] = $GLOBALS['TSFE']->sys_page->getRecordOverlay(
            'sys_file_metadata', $val, $GLOBALS['TSFE']->sys_language_uid, ''
        );
    }

    // Remove first img from the image array in single view if the TSvar firstImageIsPreview is set
    if (((count($arDamFiles) > 1 && $pObj->config['firstImageIsPreview'])
        || (count($arDamFiles) >= 1 && $pObj->config['forceFirstImageIsPreview']))
        && $pObj->theCode == 'SINGLE'
    ) {
        array_shift($arDamFiles);
        array_shift($arDamRows);
        array_shift($arImageCaptions);
        array_shift($arImageAltTexts);
        array_shift($arImageTitleTexts);
        $bShift = true;
    }

    // Get img array parts for single view pages
    if ($pObj->piVars[$pObj->pObj['singleViewPointerName']]) {
        $spage = $pObj->piVars[$pObj->config['singleViewPointerName']];
        $nArrayStart = $nImageNum * $spage;
        $arDamFiles = array_slice($arDamFiles, $nArrayStart, $nImageNum);
        $arDamRows = array_slice($arDamRows, $nArrayStart, $nImageNum);
        $arImageCaptions = array_slice($arImageCaptions, $nArrayStart, $nImageNum);
        $arImageAltTexts = array_slice($arImageAltTexts, $nArrayStart, $nImageNum);
        $arImageTitleTexts = array_slice($arImageTitleTexts, $nArrayStart, $nImageNum);
    }

    while (list($key, $val) = each($arDamFiles)) {
        if ($nCc == $nImageNum) {
            break;
        }

        $strCaption = '';

        if ($val) {
            //set Caption, Alt-text and Title Tag
            switch ($strMode) {
            //take data form tt_news record
            case 0:
                $arImageConf['image.']['altText'] = $arImageAltTexts[$nCc];
                $arImageConf['image.']['titleText'] = $arImageTitleTexts[$nCc];
                $strCaption = $arImageCaptions[$nCc];
                break;
            //if fields are empty in news record, take data from DAM fields
            case 1:
                if ($arImageAltTexts[$nCc]) {
                    $arImageConf['image.']['altText'] = $arImageAltTexts[$nCc];
                } else {
                    $arImageConf['image.']['altText'] = $arDamRows[$key]['alt_text'];
                }

                if ($arImageTitleTexts[$nCc]) {
                    $arImageConf['image.']['titleText'] = $arImageTitleTexts[$nCc];
                } else {
                    $arImageConf['image.']['titleText'] = $arDamRows[$key]['title'];
                }

                if ($arImageCaptions[$nCc]) {
                    $strCaption = $arImageCaptions[$nCc];
                } else {
                    $strCaption = $arDamRows[$key]['caption'];
                }
                break;
            //take data from DAM fields
            case 2:
                $arImageConf['image.']['altText'] = $arDamRows[$key]['alt_text'];
                $arImageConf['image.']['titleText'] = $arDamRows[$key]['title'];
                $strCaption = $arDamRows[$key]['caption'];
                break;
            }

            $arImageConf['image.']['file'] = $val;
        }

        $pObj->local_cObj->setCurrentVal($val);

        // enables correct use of extension perfectlightbox
        if ($bShift) {
            $GLOBALS['TSFE']->register['IMAGE_NUM_CURRENT'] = $nCc + 1;
        } else {
            $GLOBALS['TSFE']->register['IMAGE_NUM_CURRENT'] = $nCc;
        }

        $strImgCode .= $pObj->local_cObj->wrap(
            $pObj->local_cObj->IMAGE($arImageConf['image.'])
            . $pObj->local_cObj->stdWrap($strCaption, $arImageConf['caption_stdWrap.']),
            $arImageConf['imageWrapIfAny_' . $nCc]
        );
        $nCc++;
    }

    // fill marker
    $arMarker['###NEWS_IMAGE###'] = '';

    if ($nCc) {
        $arMarker['###NEWS_IMAGE###'] = $pObj->local_cObj->wrap(
            trim($strImgCode), $arImageConf['imageWrapIfAny']
        );
    } else {
        // noImage_stdWrap
        $arMarker['###NEWS_IMAGE###'] = $pObj->local_cObj->stdWrap(
            $arMarker['###NEWS_IMAGE###'], $arImageConf['image.']['noImage_stdWrap.']
        );
    }

    return $arMarker;
}
?>
