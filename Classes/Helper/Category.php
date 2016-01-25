<?php
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
 * helper class to get mapped categories from dam_category
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

class Category
{
    /**
     * fetches the uid from category-name
     *
     * @param string $name category-name
     *
     * @return integer category-name
     */
    public static function getCategoryByName($name)
    {
        /* @var $db \TYPO3\CMS\Core\Database\DatabaseConnection */
        $db = $GLOBALS['TYPO3_DB'];
        $cat = $db->exec_SELECTgetSingleRow(
            'uid',
            'sys_category',
            'title = "' . $name . '"'
        );
        $catUid = $cat['uid'];
        return $catUid;
    }

    /**
     * fetches category-uids from a file
     *
     * @param \TYPO3\CMS\Core\Resource\File $file a file
     *
     * @return array category-uids
     */
    public static function getCategoriesFromFile($file)
    {
        $uid = $file->getUid();
        /* @var $db \TYPO3\CMS\Core\Database\DatabaseConnection */
        $db = $GLOBALS['TYPO3_DB'];
        $arCategories = $db->exec_SELECTgetRows(
            'uid_local',
            'sys_category_record_mm',
            'uid_foreign = ' . $uid
        );
        $categories = array();
        foreach ($arCategories as $category) {
            $categories[] = $category['uid_local'];
        }
        return $categories;
    }

    /**
     * fetches files from given category
     *
     * @param integer $catUid category-uid
     * 
     * @return array
     */
    public static function getFilesFromCategory($catUid)
    {
        /* @var $db \TYPO3\CMS\Core\Database\DatabaseConnection */
        $db = $GLOBALS['TYPO3_DB'];
        $db->store_lastBuiltQuery = 1;
        $collection = \TYPO3\CMS\Frontend\Category\Collection\CategoryCollection
            ::load($catUid, true, 'sys_file_metadata', 'categories');
        $arFiles = $collection->getItems();
        $fileRepo = \TYPO3\CMS\Core\Utility\GeneralUtility
            ::makeInstance('\TYPO3\CMS\Core\Resource\FileRepository');
        /* @var $fileRepo \TYPO3\CMS\Core\Resource\FileRepository */
        $files = array();
        foreach ($arFiles as $file) {
            $files[] = $fileRepo->findByUid($file['uid']);
        }
        return $files;
    }

    /**
     * Small helper function that fetches all dam categories, resolves their
     * parents and returns an array of all found categories with amalgamented
     * title values
     *
     * @return array map of category id and corresponding titles
     * (including parent titles)
     */
    public static function getDamCategories()
    {
        global $TYPO3_DB;
        $tx_dam_cat = $TYPO3_DB->exec_SELECTgetRows(
            "uid, title, parent", "sys_category",
            "deleted <> 1 AND hidden <> 1"
        );

        $arCategories = array();

        // rebuild map so that we can easily fetch the parent(s) of every category
        foreach ($tx_dam_cat as $category) {
            $arCategories[$category['uid']] = array(
                "title" => (
                    mb_convert_encoding(
                        $category["title"], "UTF-8",
                        mb_detect_encoding($category["title"])
                    )),
                'parent' => $category["parent"]
            );
        }

        foreach ($arCategories as $values) {
            // find all parents
            $parent_id = $values['parent'];
            while ($parent_id != 0) {
                $values['title'] .= ($values['title'] != "" ? "," . $values['title']
                    : $values['title']);
                $parent_id = $arCategories[$parent_id]['parent'];
            }
        }

        return $arCategories;
    }
}
?>
