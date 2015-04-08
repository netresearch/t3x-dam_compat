<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;

class tx_dam_tsfe
{
    /**
     * Used to fetch a file list for TypoScript cObjects
     *
     *    tt_content.textpic.20.imgList >
     *    tt_content.textpic.20.imgList.cObject = USER
     *    tt_content.textpic.20.imgList.cObject {
     *        userFunc = tx_dam_divFe->fetchFileList
     *
     * @param  mixed        $content: ...
     * @param  array        $conf: ...
     * 
     * @deprecated Use FILES content object
     * 
     * @return string comma list of files with path
     */
    function fetchFileList($content, $conf) {
        $refField = trim($this->cObj->stdWrap($conf['refField'], $conf['refField.']));

        $refTable = 'tt_content';
        if (isset($conf['refTable']) || isset($conf['refTable.'])) {
            $table = trim($this->cObj->stdWrap($conf['refTable'], $conf['refTable.']));
            if (!empty($table) && is_array($GLOBALS['TCA'][$table])) {
                    $refTable = $table;
            }
        }

        $refUid = $this->cObj->data['_LOCALIZED_UID'] ? $this->cObj->data['_LOCALIZED_UID'] : $this->cObj->data['uid'];
        if (isset($conf['refUid']) || isset($conf['refUid.'])) {
            $uid = trim($this->cObj->stdWrap($conf['refUid'], $conf['refUid.']));
            if (!empty($uid)) {
                $refUid = $uid;
            }
        }

        if (isset($GLOBALS['BE_USER']->workspace) && $GLOBALS['BE_USER']->workspace !== 0) {
            $workspaceRecord = $GLOBALS['TSFE']->sys_page->getWorkspaceVersionOfRecord(
                $GLOBALS['BE_USER']->workspace,
                $refTable,
                $refUid,
                'uid'
            );

            if (is_array($workspaceRecord)) {
                $refUid = $workspaceRecord['uid'];
            }
        }

        $files = $this->getAdditionalFiles($conf);
        foreach ($this->getReferencedFiles($refTable, $refField, $refUid) as $file) {
            $files[] = $file->getUid();
        }

        return implode(',', $files);
    }
    
    protected function getAdditionalFiles($conf)
    {
        $filePath = $this->cObj->stdWrap($conf['additional.']['filePath'], $conf['additional.']['filePath.']);
        $fileList = trim($this->cObj->stdWrap($conf['additional.']['fileList'], $conf['additional.']['fileList.']));
        $files = array();
        foreach (GeneralUtility::trimExplode(',', $fileList, true) as $file) {
            $files[] = $filePath . $file;
        }
        return $files;
    }

    protected function getReferencedFiles($table, $field, $uid, $slide = false)
    {
        /* @var $repository \TYPO3\CMS\Core\Resource\FileRepository */
        $repository = GeneralUtility::makeInstance(
            'TYPO3\\CMS\\Core\\Resource\\FileRepository'
        );        
        
        if ($slide && $table == 'pages') {
            // Get the rootline
            $rootline = $GLOBALS['TSFE']->sys_page->getRootLine($uid);
            // Move up the rootpage until a non-empty reference to files is found
            foreach ($rootline as $page) {
                $damFiles = $repository->findByRelation($table, $field, $page['uid']);
                if ($damFiles) {
                    break;
                }
            }
        } else {
            $damFiles = $repository->findByRelation($table, $field, $uid);
        }

        return !is_array($damFiles) ? array() : $damFiles;
    }
}