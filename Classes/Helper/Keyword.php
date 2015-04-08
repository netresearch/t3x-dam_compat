<?php
declare (encoding = 'UTF-8');
/**
 * See class comment
 *
 * PHP version 5
 *
 * @category   Netresearch
 * @package    DAM
 * @subpackage Helper
 * @author     Thomas Schöne <thomas.schoene@netresearch.de>
 * @license    http://www.netresearch.de Netresearch Copyright
 * @link       http://www.netresearch.de
 */

namespace Tx\Dam\Helper;

/**
 * helper class to get files from dam-keywords
 *
 * PHP version 5
 *
 * @category   Netresearch
 * @package    DAM
 * @subpackage Helper
 * @author     Thomas Schöne <thomas.schoene@netresearch.de>
 * @license    http://www.netresearch.de Netresearch Copyright
 * @link       http://www.netresearch.de
 */

class Keyword
{
    /**
     * fetches files from FAL by keywords
     * 
     * @param string $keyword the keywords to find files for
     * 
     * @return array array of files
     */
    public static function getFilesByKeyword($keyword)
    {
        /* @var $db \TYPO3\CMS\Core\Database\DatabaseConnection */
        $db = $GLOBALS['TYPO3_DB'];
        $keyword = $db->fullQuoteStr($keyword, 'sys_file_metadata');
        $rows = $db->exec_SELECTgetRows(
            'file',
            'sys_file_metadata',
            'keywords = ' . $keyword
        );
        $fileRepo = \TYPO3\CMS\Core\Utility\GeneralUtility
            ::makeInstance('\TYPO3\CMS\Core\Resource\FileRepository');
        /* @var $fileRepo \TYPO3\CMS\Core\Resource\FileRepository */
        $files = array();
        foreach ($rows as $uid) {
            $files[] = $fileRepo->findByUid(intval($uid['file']));
        }
        return $files;
    }
}
?>
