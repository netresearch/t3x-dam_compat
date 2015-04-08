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
 * Hook into TCA Generation and rewrite DAM fields to FAL fields
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
class ExtensionTables
    implements \TYPO3\CMS\Core\Database\TableConfigurationPostProcessingHookInterface
{
    /**
     * Map all DAM fields to FAL fields
     * 
     * @global array $TCA
     * 
     * @return void
     */
    public function processData()
    {
        global $TCA;
        foreach ($TCA as $table => $configuration) {
            foreach ((array) $configuration['columns'] as $column => $info) {
                if (TcaUtility::isDamConfig($info['config'])) {
                    $TCA[$table]['columns'][$column]['config']
                        = TcaUtility::convertDamToFalConfig($info['config']);
                }
            }
        }
    }
}
?>
