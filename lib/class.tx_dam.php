<?php
declare(encoding = 'UTF-8');
/**
 * DAM replace
 *
 * PHP version 5
 *
 * @category Netresearch
 * @package  DAM
 * @author   Michael Kunze <michael.kunze@netresearch.de>
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
 * @author   Michael Kunze <michael.kunze@netresearch.de>
 * @license  http://www.netresearch.de Netresearch Copyright
 * @link     http://www.netresearch.de
 */
class tx_dam
{
    /**
     * @deprecated This is only a stub for compatibility
     *
     * @return void
     */
    function register_action()
    {
        // TODO: Figure out, what actions were and if they still play a role
    }

    /**
     * Fetches a media object from the index by a given UID.
     *
     * @param integer $uid UID of the meta data record
     *
     * @return object media object or false
     */
    function media_getByUid($uid)
    {
        /* @var $media tx_dam_media */
        $media = \TYPO3\CMS\Core\Utility\GeneralUtility
            ::makeInstance('tx_dam_media', $uid);

        return $media;
    }

    /**
     * Fetches the meta data from the index by a given file path or file info array.
     * The field list to be fetched can be passed.
     *
     * @param mixed  $fileInfo Is a file path or an array containing a file 
     *                         info from tx_dam::file_compileInfo().
     * @param string $fields   A list of fields to be fetched. Default is a 
     *                         list of fields generated
     *
     * @deprecates use FAL instead
     *
     * @return array
     */
    public function meta_getDataForFile($fileInfo, $fields='')
    {
        /* @var $factory \TYPO3\CMS\Core\Resource\ResourceFactory */
        $factory = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
            '\TYPO3\CMS\Core\Resource\ResourceFactory'
        );
        $file = $factory->getFileObjectFromCombinedIdentifier($fileInfo);
        return $file->getMetaData();
    }
  
    /**
     * Returns an array which describes the type of a file.
     *
     * example:
     * $mimeType = array();
     * $mimeType['file_mime_type'] = 'audio';
     * $mimeType['file_mime_subtype'] = 'x-mpeg';
     * $mimeType['file_type'] = 'mp3';
     *
     * @param mixed $fileInfo Is a file path or an array containing a file info from
     *                        tx_dam::file_compileInfo().
     * 
     * @deprecated use FAL instead
     *
     * @return array          Describes the type of a file
     */
    public function file_getType($fileInfo)
    {
        $mimeType = array();

        if (is_array($fileInfo) and $fileInfo['file_mime_type']) {
            $mimeType = array();
            $mimeType['file_mime_type'] = $fileInfo['file_mime_type'];
            $mimeType['file_mime_subtype'] = $fileInfo['file_mime_subtype'];
            $mimeType['file_type'] = $fileInfo['file_type'];
        } else {
            /* @var $factory \TYPO3\CMS\Core\Resource\ResourceFactory */
            $factory = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
                '\TYPO3\CMS\Core\Resource\ResourceFactory'
            );
            
            $file = $factory->getFileObjectFromCombinedIdentifier($fileInfo);
            /* @var $db \TYPO3\CMS\Core\Database\DatabaseConnection */
            $db = $GLOBALS['TYPO3_DB'];
            
            $mimeType['file_type'] = $file->getExtension();
            list($mimeType['file_mime_type'], $mimeType['file_mime_subtype']) 
                = explode('/', $file->getMimeType());
        }

        return $mimeType;
    }
    
    /**
     * Check if a user is allowed to process a file action like rename or delete.
     *
     * Currently for BE usage only
     *
     * @param string $action Action name: deleteFile, moveFolder, ... .
     *                       If empty the whole permission array will be returned.
     *
     * @deprecated use FAL instead
     *
     * @return	mixed	     Returns an array with action permissions if $action is 
     *                       empty. Otherwise true or false will be returned
     *                       depending if the action is allowed.
     *
     */
    function access_checkFileOperation ($action='')
    {

        if (!is_object($GLOBALS['BE_USER'])) {
                return false;
        }

        if ($action and $GLOBALS['BE_USER']->isAdmin()) {
                return true;
        }

        $setup = $GLOBALS['BE_USER']->getFileoperationPermissions();
        // Files: Upload,Copy,Move,Delete,Rename
        if (($setup&1)==1) {
            $actionPerms['uploadFile'] = true;
            $actionPerms['copyFile'] = true;
            $actionPerms['moveFile'] = true;
            $actionPerms['deleteFile'] = true;
            $actionPerms['renameFile'] = true;
            $actionPerms['editFile'] = true;
            $actionPerms['newFile'] = true;
        }// Files: Unzip
        if (($setup&2)==2) {
            $actionPerms['unzipFile'] = true;
        }// Directory: Move,Delete,Rename,New
        if (($setup&4)==4) {
            $actionPerms['moveFolder'] = true;
            $actionPerms['deleteFolder'] = true;
            $actionPerms['renameFolder'] = true;
            $actionPerms['newFolder'] = true;
        }// Directory: Copy
        if (($setup&8)==8) {
            $actionPerms['copyFolder'] = true;
        }// Directory: Delete recursively (rm -Rf)
        if (($setup&16)==16) {
            $actionPerms['deleteFolderRecursively'] = true;
        }

        if ($action) {
            return $actionPerms[$action];
        } else {
            return $actionPerms;
        }
    }

    /**
     * Process indexing for the given file, folder or a list of files and folders.
     * This function can be used if a setup for indexing is available of callback
     * function shall be used.
     * But simply a file name can be passed to and everything goes automatically.
     *
     * @param mixed $filename         A single filename or folder path or a list of
     *                                files and paths as array. If it is an array the
     *                                values can be file path or array:
     *                                array('processFile' => 'path to file that should be indexed',
     *                                'metaFile' => 'additional file that holds meta data for the processFile')
     * @param mixed $setup            Setup as string (serialized setup) or array.
     *                                See tx_dam_indexing::restoreSerializedSetup()
     * @param mixed $callbackFunc     Callback function for the finished indexed file.
     * @param mixed $metaCallbackFunc Callback function which will be called during 
     *                                indexing to allow modifications to the meta data.
     *
     * @return array Info array about indexed files and meta data records.
     * @deprecated use FAL instead
     */
    function index_process(
        $filename,
        $setup=null,
        $callbackFunc=null,
        $metaCallbackFunc=null
    ) {
        /* @var $storageRepo \TYPO3\CMS\Core\Resource\StorageRepository */
        $storageRepo = \TYPO3\CMS\Core\Utility\GeneralUtility
                ::makeInstance('\TYPO3\CMS\Core\Resource\StorageRepository');
        $storage = $storageRepo->findByUid(1);
        $file = $storage->getFile($filename);

        /* @var $fileRepo \TYPO3\CMS\Core\Resource\FileRepository */
        $fileRepo = \TYPO3\CMS\Core\Utility\GeneralUtility
                ::makeInstance('\TYPO3\CMS\Core\Resource\FileRepository');

        return $fileRepo->addToIndex($file);

    }

}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/dam/lib/class.tx_dam.php']) {
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/dam/lib/class.tx_dam.php']);
}
?>
