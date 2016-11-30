<?php

/**
 * See class comment
 *
 * PHP version 5
 *
 * @category   Netresearch
 * @package    DAM
 * @subpackage Record
 * @author     Thomas Schöne <thomas.schoene@netresearch.de>
 * @license    http://www.netresearch.de Netresearch Copyright
 * @link       http://www.netresearch.de
 */

namespace Tx\Dam\Model;

/**
 * A Dam-Fileobject
 *
 * PHP version 5
 *
 * @category   Netresearch
 * @package    DAM
 * @subpackage Record
 * @author     Thomas Schöne <thomas.schoene@netresearch.de>
 * @license    http://www.netresearch.de Netresearch Copyright
 * @link       http://www.netresearch.de
 */

class TxDamRecord extends \ArrayObject
{
    /* @var \TYPO3\CMS\Core\Resource\File */
    protected $file;

    /* @var string comma separated list of categories*/
    public $category;


    /**
     * constructor needs a file-object, an uid or an array
     * 
     * @param mixed $file file-object, uid or row as array
     * 
     * @return void
     */
    public function __construct($file)
    {
        switch (true) {
        case is_numeric($file):
            $this->file = \TYPO3\CMS\Core\Resource\ResourceFactory
                ::getInstance()->getFileObject($file);
            break;
        case is_array($file):
            $this->file = \TYPO3\CMS\Core\Resource\ResourceFactory
                ::getInstance()->getFileObject($file['uid'], $file);
            break;
        case is_object($file):
            $this->file = $file;
            break;
        default:
            throw new \Tx\Dam\Exception\Exception(
                'You need an uid, an array or an object to '
                . 'instantiate TxDamRecord'
            );
        }
        $properties = $this->mapProperties($this->file->getProperties());
        parent::__construct($properties);
    }

    /**
     * gets the file
     *
     * @return \TYPO3\CMS\Core\Resource\File
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Returns the value at the specified index
     *
     * @param string $index The index with the value.
     *
     * @return mixed The value at the specified index or NULL
     */
    public function offsetGet($index)
    {
        if ($index === 'category') {
            return $this->getCategory();
        }
        return parent::offsetGet($index);
    }

    /**
     * Returns whether the requested index exists
     *
     * @param type $index The index being checked
     *
     * @return void
     */
    public function offsetExists($index)
    {
        if ($index === 'category') {
            return true;
        }
        return parent::offsetExists($index);
    }

    /**
     * fetches the categories from db and sets them
     *
     * @return void
     */
    protected function setCategory()
    {
        /* @var $db \TYPO3\CMS\Core\Database\Databaseconnection */
        $db = $GLOBALS['TYPO3_DB'];
        $rows = $db->exec_SELECTgetRows(
            'DISTINCT uid',
            'sys_category_record_mm as mm '
            . 'INNER JOIN sys_category as cat ON mm.uid_local = cat.uid',
            'mm.uid_foreign = ' . $this['uid']
        );
        foreach ($rows as $cat) {
            $arCatUids[] = $cat['uid'];
        }
        $this->category = implode(',', $arCatUids);
    }

    /**
     * getter for categories
     *
     * @return string commaseparated list of categories
     *
     */
    protected function getCategory()
    {
        if ($this->category === null) {
            $this->setCategory();
        }
        return $this->category;
    }

    /**
     * maps fal-properties to dam properties
     *
     * @param array $properties fal-properties
     *
     * @return array dam-properties
     */
    protected function mapProperties($properties)
    {
        $type = explode('/', $properties['mime_type']);
        if ($this->file->getStorage()->hasFile($this->file->getIdentifier())) {
            $path = $this->file->getPublicUrl();
            $properties['file_hash'] = $this->file->getSha1();
            $properties['file_name'] = basename($path);
            $properties['file_path'] = dirname(trim($path, '/')) . '/';
        }
        $properties['file_mime_type'] = $type[0];
        $properties['file_mime_subtype'] = $type[1];
        $properties['file_type'] = $this->file->getExtension();
        $properties['file_size'] = $properties['size'];
        $properties['hpixels'] = $properties['width'];
        $properties['vpixels'] = $properties['height'];
        $properties['alt_text'] = $properties['alternative'];

        return $properties;
    }
}

?>
