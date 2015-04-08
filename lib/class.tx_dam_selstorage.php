<?php
declare (encoding = 'UTF-8');
/**
 * DAM selStorage replace
 *
 * PHP version 5
 *
 * @category   Netresearch
 * @package    DAM
 * @subpackage SelStorage
 * @author     Thomas Schöne <thomas.schoene@netresearch.de>
 * @license    http://www.netresearch.de Netresearch Copyright
 * @link       http://www.netresearch.de
 */

/**
 * DAM selStorage replace
 *
 * PHP version 5
 *
 * @category   Netresearch
 * @package    DAM
 * @subpackage SelStorage
 * @author     Thomas Schöne <thomas.schoene@netresearch.de>
 * @license    http://www.netresearch.de Netresearch Copyright
 * @link       http://www.netresearch.de
 */
class tx_dam_selStorage
{
    /**
     * The stored settings array
     */
    var $storedSettings = array();

    /**
     * Message from the last storage command
     */
    var $msg = '';


    /**
     * Name of the form. Needed for JS
     */
    var $formName = 'selStoreControl';


    /**
     * Name of the storage table
     */
    var $table = 'sys_file_collection';

    /**
     * write messages into the devlog?
     */

    var $writeDevLog = 0; 				
    
    /**
     * Initializes the object
     *
     * @return	void
     */
    function init()
    {
        // enable dev logging if set
        if ($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.tx_dam_selStorage.php']['writeDevLog']) {
            $this->writeDevLog = true;
        }
        if (TYPO3_DLOG) {
            $this->writeDevLog = true;
        }
    }

}

?>
