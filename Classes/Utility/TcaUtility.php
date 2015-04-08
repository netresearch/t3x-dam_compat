<?php
declare (encoding = 'UTF-8');

/**
 * See class comment
 *
 * PHP version 5
 *
 * @category   Netresearch
 * @package    DAM
 * @subpackage Utility
 * @author     Christian Opitz <christian.opitz@netresearch.de>
 * @license    http://www.netresearch.de Netresearch Copyright
 * @link       http://www.netresearch.de
 */

namespace Tx\Dam\Utility;
use \TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

/**
 * Utility to deal with TCA configurations
 *
 * PHP version 5
 *
 * @category   Netresearch
 * @package    DAM
 * @subpackage Utility
 * @author     Christian Opitz <christian.opitz@netresearch.de>
 * @license    http://www.netresearch.de Netresearch Copyright
 * @link       http://www.netresearch.de
 */
class TcaUtility
{
    /**
     * Determine if a field is a DAM relation
     * 
     * @param array $config The field configuration from flexform or TCA
     * 
     * @return boolean
     */
    public static function isDamConfig($config)
    {
        return
            $config['type'] == 'group' 
            && $config['internal_type'] == 'db' 
            && $config['allowed'] == 'tx_dam' 
            && $config['MM'] == 'tx_dam_mm_ref';
    }

    /**
     * Rewrite tx_dam group fields to sys_file inline fields
     * 
     * Assumption: DAM files and references have been migrated to FAL files and 
     *             references before
     * 
     * @param array $config The field configuration from flexform or TCA
     * 
     * @return array
     */
    public static function convertDamToFalConfig($config)
    {
        $newConfig = ExtensionManagementUtility::getFileFieldTCAConfig(
            $config['MM_match_fields']['ident'],
            array(),
            $config['allowed_types'],
            $config['disallowed_types']
        );
        if ($config['MM_match_fields']['tablenames']) {
            $newConfig['foreign_match_fields']['tablenames']
                = $config['MM_match_fields']['tablenames'];
        }
        foreach (array('size', 'minitems', 'maxitems') as $field) {
            if (array_key_exists($field, $config)) {
                $newConfig[$field] = $config[$field];
            }
        }
        if (!$config['show_thumbs']) {
            $newConfig['appearance']['headerThumbnail'] = false;
        }
        $newConfig['foreign_selector_fieldTcaOverride']['config']['appearance']
            ['fileUploadAllowed'] = false;
        return $newConfig;
    }

    /**
     * Determine if a field is rendering a selecttree
     *
     * @param array $config The field configuration from flexform or TCA
     *
     * @return boolean      Wether renders a tree or not
     */
    public static function isTreeViewConfig($config)
    {
        return isset($config['treeViewBrowseable'])
            && $config['foreign_table'] == 'tx_dam_cat';
    }

    /**
     * Maps the config to render the Treeview without a userfunc
     * 
     * @param array $config The field configuration from flexform or TCA
     * 
     * @return array
     */
    public static function convertTreeViewToFalConfig($config)
    {
        $newConfig = array();
        $newConfig['type'] = $config['type'];
        $newConfig['renderMode'] = 'tree';
        $newConfig['foreign_table'] = 'sys_category';
        $newConfig['minitems'] = $config['minitems'];
        $newConfig['maxitems'] = $config['maxitems'];
        $newConfig['treeConfig'] = array(
            'parentField' => 'parent',
            'appearance' => array(
                'expandAll' => true,
                'showHeader' => true
            )
        );

        return $newConfig;
    }
}
?>
