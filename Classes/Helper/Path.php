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
 * helper to get path information
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

class Path
{
    /**
     * fetches the filepath for a given fal-uid
     * 
     * @param integer $uid uid of the file
     * 
     * @return string filepath
     */
    public static function getPathByUid($uid)
    {
        /* @var $fileRepo \TYPO3\CMS\Core\Resource\FileRepository */
        $fileRepo = \TYPO3\CMS\Core\Utility\GeneralUtility
            ::makeInstance('\TYPO3\CMS\Core\Resource\FileRepository');
        $file = $fileRepo->findByUid($uid);
        $path = $file->getPublicUrl();
        return $path;
    }
}
?>
