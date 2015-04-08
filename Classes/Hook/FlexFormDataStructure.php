<?php
declare (encoding = 'UTF-8');

/**
 * See class comment
 *
 * PHP version 5
 *
 * @category   Netresearch
 * @package    DAM
 * @subpackage Hook
 * @author     Christian Opitz <christian.opitz@netresearch.de>
 * @license    http://www.netresearch.de Netresearch Copyright
 * @link       http://www.netresearch.de
 */

namespace Tx\Dam\Hook;
use Tx\Dam\Utility\TcaUtility;

/**
 * Hook into FlexForm DataStructure Generation and rewrite DAM fields to FAL fields
 * 
 * Assumption: DAM files and references have been migrated to FAL files and 
 *             references before
 *
 * PHP version 5
 *
 * @category   Netresearch
 * @package    DAM
 * @subpackage Hook
 * @author     Christian Opitz <christian.opitz@netresearch.de>
 * @license    http://www.netresearch.de Netresearch Copyright
 * @link       http://www.netresearch.de
 */
class FlexFormDataStructure
{
    /**
     * Hook method called at the end of
     * {@see \TYPO3\CMS\Backend\Utility\BackendUtility::getFlexFormDS()}
     * 
     * @param array $dataStructArray The DS starting with ROOT
     * 
     * @return void
     */
    public function getFlexFormDS_postProcessDS(&$dataStructArray)
    {
        if (!is_array($dataStructArray)) {
            // This can actually happen and result in fatal errors below
            return;
        }
        if (array_key_exists('sheets', $dataStructArray)) {
            foreach ($dataStructArray['sheets'] as &$ds) {
                $this->mapDamFieldsToFal($ds);
                $this->mapTreeViewFieldsToFal($ds);
            }
        } else {
            $this->mapDamFieldsToFal($dataStructArray);
        }
    }
    
    /**
     * Rewrite tx_dam group fields to sys_file inline fields
     * 
     * @param array $ds The datastructure inside sheets
     * 
     * @return void
     */
    protected function mapDamFieldsToFal(&$ds)
    {
        foreach ($ds['ROOT']['el'] as &$el) {
            if (TcaUtility::isDamConfig($el['TCEforms']['config'])) {
                $el['TCEforms']['config']
                    = TcaUtility::convertDamToFalConfig($el['TCEforms']['config']);
            }
        }
    }

    /**
     * Enables Treerendering without userFunc
     *
     * @param array $ds The datastructure inside sheets
     *
     * @return void
     */
    protected function mapTreeViewFieldsToFal(&$ds)
    {
        foreach ($ds['ROOT']['el'] as &$el) {
            if (TcaUtility::isTreeViewConfig($el['TCEforms']['config'])) {
                $el['TCEforms']['config']
                    = TcaUtility::convertTreeViewToFalConfig($el['TCEforms']['config']);
            }
        }
    }

}
?>
