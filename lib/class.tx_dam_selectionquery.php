<?php
/**
 * DAM selectionquery replace
 *
 * PHP version 5
 *
 * @category   Netresearch
 * @package    DAM
 * @subpackage Selectionquery
 * @author     Thomas Schöne <thomas.schoene@netresearch.de>
 * @license    http://www.netresearch.de Netresearch Copyright
 * @link       http://www.netresearch.de
 */

/**
 * DAM selectionquery replace
 *
 * PHP version 5
 *
 * @category   Netresearch
 * @package    DAM
 * @subpackage Selectionquery
 * @author     Thomas Schöne <thomas.schoene@netresearch.de>
 * @license    http://www.netresearch.de Netresearch Copyright
 * @link       http://www.netresearch.de
 */
class tx_dam_selectionquery
{
    /**
     * Initializes the query generator object
     *
     * @return	void
     */
    function initQueryGen()
    {
        global $TYPO3_CONF_VARS;
        $this->qg = t3lib_div::makeInstance('tx_dam_querygen');
    }
}

?>
