<?php
declare(encoding = 'UTF-8');
/**
 * See Class Comment.
 *
 * PHP version 5
 *
 * @category   Netresearch
 * @package    DAM
 * @subpackage Exception
 * @author     Axel Kummer <axel.kummer@netresearch.de>
 * @license    http://www.netresearch.de Netresearch Copyright
 * @link       http://www.netresearch.de
 */
namespace Tx\Dam\Xclass;

use TYPO3\CMS\Core\Resource\File as File;
use TYPO3\CMS\Core\Resource\FileReference as FileReference;


/**
 * XClass of ContentObjectRenderer.
 *
 * ContentObjectRenderer has to be overwritten to force treatIdAsReference for
 * images which are fetched by the tx_dam_tsfe->fetchFileList
 * and tx_damtvc_tsfe->fetchFileList, because returned ids of these methods only
 * reference ids.
 *
 * @category   Netresearch
 * @package    DAM
 * @subpackage Exception
 * @author     Axel Kummer <axel.kummer@netresearch.de>
 * @license    http://www.netresearch.de Netresearch Copyright
 * @link       http://www.netresearch.de
 */
class ContentObjectRenderer
    extends \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer
{
    /**
     * Name of the dam tsfe class
     *
     * @var string
     */
    const CLASS_DAM_TFSE = 'tx_dam_tsfe';

    /**
     * Name of the dam tsfe tv-connector class
     *
     * @var string
     */
    const CLASS_DAMTVC_TFSE = 'tx_damtvc_tsfe';

    /**
     * Name of the config key to set if a resource id is handles as reference
     *
     * @var string
     */
    const KEY_TREAT_AS_REFERENCE = 'treatIdAsReference';


    /**
     * Returns true if the image should be treated as reference and not as
     * resource
     *
     * @param array $fileArray Array with file configuration
     *
     * @return bool
     */
    protected function treatIdAsReference($fileArray)
    {
        $bTreatIdAsReference = $fileArray[self::KEY_TREAT_AS_REFERENCE];

        if (false === (bool) $bTreatIdAsReference) {
            return $this->isDamTsfeUsed($fileArray);
        }

        return $bTreatIdAsReference;
    }

    /**
     * Returns true if the dam tsfe methods are used.
     *
     * @param array $fileArray Array with file configuration
     *
     * @return bool
     */
    protected function isDamTsfeUsed($fileArray)
    {
        $strFileArray = json_encode($fileArray);

        if (false === strpos($strFileArray, 'userFunc')
            && null === $fileArray[self::KEY_TREAT_AS_REFERENCE]
        ) {
            return true;
        }


        $bDamMethodUsed = strpos(
            $strFileArray, self::CLASS_DAM_TFSE.'->fetchFileList'
        );

        if ($bDamMethodUsed !== false) {
            return true;
        }

        return false !== strpos(
            $strFileArray, self::CLASS_DAMTVC_TFSE.'->fetchFileList'
        );
    }

    /**
     * Creates and returns a TypoScript "imgResource".
     * The value ($file) can either be a file reference (TypoScript resource) or the string "GIFBUILDER".
     * In the first case a current image is returned, possibly scaled down or otherwise processed.
     * In the latter case a GIFBUILDER image is returned; This means an image is made by TYPO3 from layers of elements as GIFBUILDER defines.
     * In the function IMG_RESOURCE() this function is called like $this->getImgResource($conf['file'], $conf['file.']);
     *
     * Structure of the returned info array:
     *  0 => width
     *  1 => height
     *  2 => file extension
     *  3 => file name
     *  origFile => original file name
     *  origFile_mtime => original file mtime
     *  -- only available if processed via FAL: --
     *  originalFile => original file object
     *  processedFile => processed file object
     *  fileCacheHash => checksum of processed file
     *
     * @param string|File|FileReference $file      A "imgResource" TypoScript
     *                                             data type.
     * @param array                     $fileArray TypoScript properties for the
     *                                             imgResource type
     *
     * @return array|NULL Returns info-array
     * @see IMG_RESOURCE(), cImage(), \TYPO3\CMS\Frontend\Imaging\GifBuilder
     */
    public function getImgResource($file, $fileArray)
    {
        if (empty($file) && empty($fileArray)) {
            return null;
        }

        if (!is_array($fileArray)) {
            $fileArray = (array) $fileArray;
        }

        if (empty($file)) {
            $fileArray[self::KEY_TREAT_AS_REFERENCE]
                = $this->treatIdAsReference($fileArray);
        }

        return parent::getImgResource($file, $fileArray);
    }
}
?>
