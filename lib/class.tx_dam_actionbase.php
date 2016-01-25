<?php
/**
 * DAM
 *
 * PHP version 5
 *
 * @category Netresearch
 * @package  DAM
 * @author   Thomas Schöne <thomas.schoene@netresearch.de>
 * @license  http://www.netresearch.de Netresearch Copyright
 * @link     http://www.netresearch.de
 */

/**
 * DAM
 *
 * PHP version 5
 *
 * @category Netresearch
 * @package  DAM
 * @author   Thomas Schöne <thomas.schoene@netresearch.de>
 * @license  http://www.netresearch.de Netresearch Copyright
 * @link     http://www.netresearch.de
 */
class tx_dam_actionBase
{
    //we keep some properties because the extending class needs them
    
    /**
     * Stores the currently requested action type:
     * icon, button, control, context
     * @var string
     */
    public $type;
    /**
     * Environment
     */
    public $env = array(
        'returnUrl' => '',
        'defaultCmdScript' => '',
        'defaultEditScript' => '',
        'backPath' => '',
    );

    /**
     * Information about the item the action should be performed on.
     */
     public $itemInfo = array();

    /**
     * Defines if the item should be rendered disabled like a greyed icon.
     * @var boolean
     */
    public $disabled;
    
    /**
     * Returns true if the action is of the wanted type.
     * 
     * @param string $type     Action type
     * @param array  $itemInfo Item info array. Eg pathInfo, meta data array
     * @param array  $env      Environment array. Can be set with setEnv() too.
     * 
     * @deprecated Use FAL instead
     * 
     * @return boolean
     */
    public function isTypeValid ($type, $itemInfo=null, $env=null)
    {
        $this->type = $type;
        if ($itemInfo) {
            $this->itemInfo = $itemInfo;
        }
        if ($env) {
            $this->env = $env;
        }
        return in_array($type, $this->typesAvailable);
    }

    /**
     * Prepend a space to an tag attribute
     *
     * @param string $attribute attribute to clean up
     * 
     * @deprecated Use FAL instead
     * 
     * @return string
     */
    public function _cleanAttribute($attribute)
    {
        return ($attribute ? ' '.$attribute : '');
    }
}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/dam/lib/class.tx_dam_actionbase.php']) {
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/dam/lib/class.tx_dam_actionbase.php']);
}
?>
