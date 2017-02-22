<?php

if (!defined('TYPO3_MODE')) die ('Access denied.');

if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('tt_news')) {
    $strLanguageFile = 'LLL:EXT:dam/Resources/Private/Language/locallang_db.xml';

    $ttNewsExtConf = unserialize(
        $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['tt_news']
    );

    $damTtNewsExtConf = unserialize(
        $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['dam']
    );

    $GLOBALS['TCA']['tt_news'] = $TCA['tt_news'];

// Override image_field
    $GLOBALS['TCA']['tt_news']['columns']['tx_damnews_dam_images'] = txdam_getMediaTCA('image_field', 'tx_damnews_dam_images');
    $GLOBALS['TCA']['tt_news']['columns']['tx_damnews_dam_images']['l10n_mode'] = ($ttNewsExtConf['l10n_mode_imageExclude'] ? 'exclude' : 'mergeIfNotBlank');
    $GLOBALS['TCA']['tt_news']['columns']['tx_damnews_dam_images']['exclude'] = 1;
    $GLOBALS['TCA']['tt_news']['columns']['tx_damnews_dam_images']['label'] = $strLanguageFile . ':tt_news.tx_damnews_dam_images';

// Override Media Field
    $GLOBALS['TCA']['tt_news']['columns']['tx_damnews_dam_media'] = txdam_getMediaTCA('media_field', 'tx_damnews_dam_media');

    $GLOBALS['TCA']['tt_news']['columns']['tx_damnews_dam_media']['l10n_mode'] = 'mergeIfNotBlank';
    $GLOBALS['TCA']['tt_news']['columns']['tx_damnews_dam_media']['exclude'] = 1;
    $GLOBALS['TCA']['tt_news']['columns']['tx_damnews_dam_media']['label'] = $strLanguageFile . ':tt_news.tx_damnews_dam_media';


    if ($damTtNewsExtConf['media_add_ref']) {
        if ($damTtNewsExtConf['media_add_orig_field']) {
            \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
                'tt_news', 'tx_damnews_dam_images', '0', 'after:image'
            );
            \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
                'tt_news', 'tx_damnews_dam_images', '1', 'after:image'
            );
            \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
                'tt_news', 'tx_damnews_dam_images', '2', 'after:image'
            );

            \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
                'tt_news', 'tx_damnews_dam_media', '0', 'after:news_files'
            );
            \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
                'tt_news', 'tx_damnews_dam_media', '1', 'after:news_files'
            );
            \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
                'tt_news', 'tx_damnews_dam_media', '2', 'after:news_files'
            );

        } else {
            $GLOBALS['TCA']['tt_news']['types']['0']['showitem'] = str_replace(
                'image;', 'tx_damnews_dam_images;',
                $GLOBALS['TCA']['tt_news']['types']['0']['showitem']
            );
            $GLOBALS['TCA']['tt_news']['types']['1']['showitem'] = str_replace(
                'image;', 'tx_damnews_dam_images;',
                $GLOBALS['TCA']['tt_news']['types']['1']['showitem']
            );
            $GLOBALS['TCA']['tt_news']['types']['2']['showitem'] = str_replace(
                'image;', 'tx_damnews_dam_images;',
                $GLOBALS['TCA']['tt_news']['types']['2']['showitem']
            );

            $GLOBALS['TCA']['tt_news']['types']['0']['showitem'] = str_replace(
                'news_files;', 'tx_damnews_dam_media;',
                $GLOBALS['TCA']['tt_news']['types']['0']['showitem']
            );
            $GLOBALS['TCA']['tt_news']['types']['1']['showitem'] = str_replace(
                'news_files;', 'tx_damnews_dam_media;',
                $GLOBALS['TCA']['tt_news']['types']['1']['showitem']
            );
            $GLOBALS['TCA']['tt_news']['types']['2']['showitem'] = str_replace(
                'news_files;', 'tx_damnews_dam_media;',
                $GLOBALS['TCA']['tt_news']['types']['2']['showitem']
            );
        }
    }

    $TCA['tt_news'] = $GLOBALS['TCA']['tt_news'];
}
