<?php
/**
 * DAM db replace
 *
 * PHP version 5
 *
 * @category   Netresearch
 * @package    DAM
 * @subpackage DB
 * @author     Thomas Schöne <thomas.schoene@netresearch.de>
 * @license    http://www.netresearch.de Netresearch Copyright
 * @link       http://www.netresearch.de
 */

/**
 * DAM db replace
 *
 * PHP version 5
 *
 * @category   Netresearch
 * @package    DAM
 * @subpackage DB
 * @author     Thomas Schöne <thomas.schoene@netresearch.de>
 * @license    http://www.netresearch.de Netresearch Copyright
 * @link       http://www.netresearch.de
 */
class tx_dam_db
{
    /**
     * Make a list of files by a mm-relation to the sys_file table which is used to
     * get eg. the references tt_content<>sys_file
     *
     * 	Returns:
     * 	array (
     * 		'files' => array(
     * 			record-uid => 'fileadmin/example.jpg',
     * 		)
     * 		'rows' => array(
     * 			record-uid => array(meta data array),
     * 		)
     * 	);
     *
     * @param string  $foreign_table Table name to get references for. Eg tt_content
     * @param integer $foreign_uid   The uid of the referenced record
     * @param mixed   $MM_ident      Array of field/value pairs that should match in
     *                               MM table. If it is a string, it will be used as
     *                               value for the field 'ident'.
     * @param string  $MM_table      The mm table to use. Default: sys_file_reference
     * @param string  $fields        The fields to select. Needs to be prepended
     *                               with table name: sys_file.uid, sys_file.title
     * @param array   $whereClauses  WHERE clauses as array with associative keys
     *                               (which can be used to overwrite 'enableFields')
     *                               or a single one as string.
     * @param string  $groupBy       ...
     * @param string  $orderBy       ...
     * @param string  $limit         Default: 1000
     * 
     * @return array		     ...
     */
    function getReferencedFiles(
        $foreign_table='',
        $foreign_uid='',
        $MM_ident='',
        $MM_table='sys_file_reference',
        $fields='',
        $whereClauses=array(),
        $groupBy='',
        $orderBy='',
        $limit=1000
    ) {
        /* @var $repository \TYPO3\CMS\Core\Resource\FileRepository */
        $repository = TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
            'TYPO3\\CMS\\Core\\Resource\\FileRepository'
        );

        $files = array();
        $fileRefs = $repository->findByRelation(
            $foreign_table,
            $MM_ident,
            (int) $foreign_uid
        );
        foreach ($fileRefs as $ref) {
            $files[] = $ref->getOriginalFile();
        }
        $paths = array();
        $rows = array();

        /* @var $file \TYPO3\CMS\Core\Resource\FileReference */
        foreach ($files as $file) {
            /* @var $damFile \Tx\Dam\Model\TxDamRecord */
            $damFile = \TYPO3\CMS\Core\Utility\GeneralUtility
                ::makeInstance('\Tx\Dam\Model\TxDamRecord', $file);
            $paths[$file->getUid()] = rawurldecode($file->getPublicUrl());
            $rows[$file->getUid()] = $damFile->getArrayCopy();

            $limit--;
            if ($limit == 0) {
                break;
            }
        }

        return array('files' => $paths, 'rows' => $rows);
    }

    /**
     * Make an array of uid's by a mm-relation to the tx_dam table which is used
     * to get eg. the references tt_content<>tx_dam
     *
     * @param string  $foreign_table Table name to get references for.
     *                               Eg tt_content
     * @param integer $foreign_uid   The uid of the referenced record
     * @param mixed   $MM_ident      Array of field/value pairs that should
     *                               match in MM table. If it is a string,
     *                               it will be used as value for the field 'ident'.
     *
     * @return array                 uid array
     */
    function getReferencesUidArray($foreign_table, $foreign_uid, $MM_ident)
    {
        $result = tx_dam_db::getReferencedFiles(
            $foreign_table,
            $foreign_uid,
            $MM_ident,
            '',
            'sys_file.uid'
        );
        return array_keys($result['rows']);
    }

    /**
     * Generates a list of sys_file db fields which are needed to get a proper info 
     * about the record.
     *
     * @return string Comma list of fields with table name prepended
     */
    function getMetaInfoFieldList()
    {
        return 'sys_file.storage,'
            . 'sys_file.type,'
            . 'sys_file.metadata,'
            . 'sys_file.extension,'
            . 'sys_file.identifier,'
            . 'sys_file.identifier_hash,'
            . 'sys_file.mime_type,'
            . 'sys_file.name,'
            . 'sys_file.sha1,'
            . 'sys_file.size,'
            . 'sys_file.creation_date,'
            . 'sys_file.modification_date,';
    }
    
    /**
     * Creates language-overlay for records in general 
     * (where translation is found in records from the same table)
     * In future versions this may support other overlays too (versions, ...)
     *
     * $conf = array(
     * 		'sys_language_uid' // sys_language uid of the wanted language
     * 		'lovl_mode' // Overlay mode. If "hideNonTranslated" then records 
     *           without translation will not be returned un-translated but false
     * )
     *
     * In FE mode sys_language_uid and lovl_mode will be get from TSFE automatically
     *
     * @param string  $table Table name
     * @param array   $row   Record to overlay. Must containt uid, pid 
     *                       and $table]['ctrl']['languageField']                   
     * @param integer $conf  Configuration array that defines the wanted overlay
     * 
     * @return mixed         Returns the input record, possibly overlaid with a 
     *                       translation. But if $OLmode is "hideNonTranslated" then
     *                       it will return false if no translation is found.
     */
    function getRecordOverlay($table, $row, $conf=array())
    {       
        if (!$conf) {
            return $row;
        }
        /* @var $pageRepo \TYPO3\CMS\Frontend\Page\PageRepository */
        $pageRepo = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
            '\TYPO3\CMS\Frontend\Page\PageRepository'
        );
        $row = $pageRepo->getRecordOverlay(
            $table,
            $row,
            intval($conf['sys_language_uid']),
            $conf['lovl_mode']
        );

        return $row;
    }
}

?>
