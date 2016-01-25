<?php
/**
 * DAM querygen replace
 *
 * PHP version 5
 *
 * @category   Netresearch
 * @package    DAM
 * @subpackage Querygen
 * @author     Thomas Schöne <thomas.schoene@netresearch.de>
 * @license    http://www.netresearch.de Netresearch Copyright
 * @link       http://www.netresearch.de
 */

/**
 * DAM querygen replace
 *
 * PHP version 5
 *
 * @category   Netresearch
 * @package    DAM
 * @subpackage Querygen
 * @author     Thomas Schöne <thomas.schoene@netresearch.de>
 * @license    http://www.netresearch.de Netresearch Copyright
 * @link       http://www.netresearch.de
 */
class tx_dam_querygen
{
    /**
     * Current query definition
     * From this definition a SQL SELECT can be build.
     */
    var $query = array();
    
    /**
     * Adds a JOIN with a MM table to the query
     *
     * @param string $mmtable          MM table (original name)
     * @param string $local_table      Local table. No default anymore
     * @param string $mmtableAlias     Alias of the MM table to be used.
     * @param string $additionalClause Additional ON clause
     * 
     * @return void
     */
    function addMMJoin($mmtable, $local_table, $mmtableAlias='', $additionalClause='')
    {
            $mmtableName = $mmtableAlias ? $mmtableAlias : $mmtable;
            $mmtableNameDef 
                = $mmtableAlias ? $mmtable.' AS '.$mmtableAlias : $mmtable ;

            $this->query['LEFT_JOIN'][$mmtableNameDef]
                = $local_table.
                '.uid='.
                $mmtableName.
                '.uid_local '.
                $additionalClause;
    }

}
?>
