<?php
declare(encoding = 'UTF-8');
/**
 * DAM replace
 *
 * PHP version 5
 *
 * @category Netresearch
 * @package  DAM
 * @author   Thomas Schöne <thomas.schoene@netresearch.de>
 * @license  http://www.netresearch.de Netresearch Copyright
 * @link     http://www.netresearch.de
 */

/**
 * DAM replace
 *
 * PHP version 5
 *
 * @category Netresearch
 * @package  DAM
 * @author   Thomas Schöne <thomas.schoene@netresearch.de>
 * @license  http://www.netresearch.de Netresearch Copyright
 * @link     http://www.netresearch.de
 */
class tx_dam_media
{
    /**
     * Holds the meta data record from DB
     */
    var $meta = null;
    /**
     * filename (basename)
     */
    var $filename = null;
    /**
     * Path to file in normalized format which is relative if possible and is like
     * the stored path in the meta data.
     */
    var $pathNormalized = null;
    /**
     * TRUE if the file exists and/or an index entry exists and accordingly to
     * $this->mode and corresponding enableFields the database query found an entry.
     */
    var $isAvailable = null;
    /**
     *
     * @var \TYPO3\CMS\Core\Resource\File
     */
    var $file;

    /**
     * the constructor
     * fetches a FAL file for a the given uid and creates this minimal version of
     * tx_dam_media
     * 
     * @param integer $uid sys_file uid
     */
    public function __construct($uid)
    {
        if (!empty($uid)) {
            $damRecord = \TYPO3\CMS\Core\Utility\GeneralUtility
                ::makeInstance('\Tx\Dam\Model\TxDamRecord', $uid);
            /* @var $damRecord \Tx\Dam\Model\TxDamRecord */
            $file = $damRecord->getFile();
            //just set some required properties
            if (isset($file)) {
                $this->isAvailable = true;
                $this->file = $damRecord->getFile();
                $this->meta = $damRecord->getArrayCopy();
                $this->pathNormalized = $damRecord['file_path'];
                $this->filename = $this->file->getName();
            }
        }
    }

    /**
     * Returns the meta-data of a sys_file_record
     *
     * @return mixed Meta data value or null
     */
    public function getMetaInfoArray()
    {
        return $this->meta;
    }

}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/dam/lib/class.tx_dam_media.php']) {
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/dam/lib/class.tx_dam_media.php']);
}
?>
