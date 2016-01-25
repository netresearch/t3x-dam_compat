<?php
/**
 * DAM replace
 *
 * PHP version 5
 *
 * @category   Netresearch
 * @package    DAM
 * @subpackage Browsetrees
 * @author     Michael Kunze <michael.kunze@netresearch.de>
 * @license    http://www.netresearch.de Netresearch Copyright
 * @link       http://www.netresearch.de
 */

/**
 * DAM replace
 *
 * PHP version 5
 *
 * @category   Netresearch
 * @package    DAM
 * @subpackage Browsetrees
 * @author     Michael Kunze <michael.kunze@netresearch.de>
 * @license    http://www.netresearch.de Netresearch Copyright
 * @link       http://www.netresearch.de
 */
class tx_dam_config
{
    /**
     * Init dam config values - which means they are fetched from TSConfig
     *
     * @param boolean $force Will force the initialization to be done again
     *                       except definedTSconfig set by config_setValue
     *
     * @deprecated Use FAL
     *
     * @return void
     */
    function init($force=false)
    {
        $config = & $GLOBALS['T3_VAR']['ext']['dam']['config'];

        $perfomMerge = false;
        if (!is_array($config)) {
            $config = array();
            $config['mergedTSconfig.'] = array();
            $config['definedTSconfig.'] = array();
        }
        if (($force || !is_array($config['userTSconfig.']))
            && ($TSconfig = tx_dam_config::_getTSconfig())
        ) {
            $config['pageUserTSconfig.'] = $config['userTSconfig.'] = $TSconfig;
            $perfomMerge = true;
        }

        if ($force || !is_array($config['pageTSconfig.'])) {
            if ($pid = tx_dam_db::getPid()
                && ($TSconfig = tx_dam_config::_getTSconfig($pid))
            ) {
                $config['pageTSconfig.'] = $TSconfig;
                $config['pageUserTSconfig.']
                    = t3lib_div::array_merge_recursive_overrule(
                        (array) $config['pageTSconfig.'],
                        (array) $config['userTSconfig.']
                    );
                $perfomMerge = true;
            }
        }

        if ($perfomMerge) {
            $config['mergedTSconfig.']
                = t3lib_div::array_merge_recursive_overrule(
                    (array) $config['pageUserTSconfig.'],
                    (array) $config['definedTSconfig.']
                );
        }
    }


    /***************************************
     *
     *   Configuration
     *
     ***************************************/

    /**
     * Return configuration values which are mainly defined by TSconfig.
     * The configPath must begin with "setup." or "mod."
     * "setup" is mapped to tx_dam TSConfig key.
     *
     * @param string  $configPath    Pointer to an "object" in the TypoScript
     *                               array, fx. 'setup.selections.default'
     * @param boolean $getProperties return the properties array instead of the
     *                               value. Means to return the stuff set by a
     *                               dot. Eg. setup.xxxx.xxx
     *
     * @deprecated Use FAL
     *
     * @return mixed Just the value or when $getProperties is set an array with
     *               the properties of the $configPath.
     */
    function getValue($configPath='', $getProperties=false)
    {
        $configValues = false;

        $config = $GLOBALS['T3_VAR']['ext']['dam']['config'];

        if (!is_array($config)) {
            tx_dam_config::init();
        }

        if ($configPath) {
            $configValues = tx_dam_config::_getTSConfigObject(
                $configPath, $config['mergedTSconfig.']
            );
        }

        if ($getProperties) {
            $configValues = $configValues['properties'];
        } else {
            $configValues = $configValues['value'];
        }

        return $configValues;
    }

    /**
     * Check a config value if its enabled
     * Anything except '' and 0 is true
     * If the the option is not set the default value will be returned
     *
     * @param string $configPath Pointer to an "object" in the TypoScript array,
     *                           fx. 'setup.selections.default'
     * @param mixed  $default    Default value when option is not set, otherwise
     *                           the value itself
     *
     * @deprecated Use FAL
     *
     * @return boolean
     */
    function checkValueEnabled($configPath, $default=false)
    {
        $parts = t3lib_div::revExplode('.', $configPath, 2);
        $config = tx_dam_config::getValue($parts[0], true);
        return tx_dam_config::isEnabledOption($config, $parts[1], $default);
    }

    /**
     * Check a config value if its enabled
     * Anything except '' and 0 is true
     *
     * @param mixed $value Value to be checked
     *
     * @deprecated Use FAL
     *
     * @return mixed Return false if value is empty or 0, otherwise the value
     *               itself
     */
    function isEnabled($value)
    {
        if (tx_dam::canBeInterpretedAsInteger($value)) {
            return intval($value) ? intval($value) : false;
        }
        return empty($value) ? false : $value;
    }

    /**
     * Check a config value if its enabled
     * Anything except '' and 0 is true
     *
     * @param array  $config  Configuration array
     * @param string $option  Option key. If not set in $config default value
     *                        will be returned
     * @param mixed  $default Default value when option is not set, otherwise
     *                        the value itself
     *
     * @deprecated Use FAL
     *
     * @return boolean
     */
    function isEnabledOption($config, $option, $default=false)
    {
        if (!isset($config[$option])) return $default;
        return tx_dam_config::isEnabled($config[$option]);
    }


    /***************************************
     *
     *   Internal
     *
     ***************************************/

    /**
     * get TSConfig values for initialization
     *
     * @deprecated Use FAL
     *
     * @access private
     * @param integer $pid If set page TSConfig will be fetched otherwise user
     *                     TSConfig
     *
     * @return array
     */
    function _getTSconfig ($pid=0)
    {
        global $TYPO3_CONF_VARS;

        $values = false;

        if (TYPO3_MODE === 'FE' && is_object($GLOBALS['TSFE'])) {
            $TSconfig = '';
            if ($pid) {
                $TSconfig = $GLOBALS['TSFE']->getPagesTSconfig($pid);
            } else {
                $TSconfig = $GLOBALS['TSFE']->fe_user->getUserTSconf();
            }

                // get global config
            $TSConfValues = tx_dam_config::_getTSConfigObject('tx_dam', $TSconfig);
            $global = $TSConfValues['properties'];

                // get plugin config
            $plugin = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_dam.'];

            $values = array('setup.' => $global, 'plugin.' => $plugin);

            // mod. properties are not used for FE


        } elseif (is_object($GLOBALS['BE_USER'])) {
            $TSconfig = '';
            if ($pid) {
                $TSconfig = t3lib_BEfunc::getPagesTSconfig($pid);
            }

                // get global config
            $TSConfValues = $GLOBALS['BE_USER']->getTSConfig('tx_dam', $TSconfig);
            $global = $TSConfValues['properties'];

                // get mod config of dam_* modules
            $TSConfValues = $GLOBALS['BE_USER']->getTSConfig('mod', $TSconfig);
            if (is_array($mod = $TSConfValues['properties'])) {
                foreach ($mod as $key => $value) {
                    if (!(substr($key, 0, 7)=='txdamM1')) {
                        unset($mod[$key]);
                    }
                }
            }
            $values = array('setup.' => $global, 'mod.' => $mod);
        }

        return $values;
    }


    /**
     * Returns the value/properties of a TS-object as given by $objectString,
     * eg. 'options.dontMountAdminMounts'
     * Nice (general!) function for returning a part of a TypoScript array!
     *
     * @param string $objectString Pointer to an "object" in the TypoScript array,
     *                             fx. 'options.dontMountAdminMounts'
     * @param array  $config       TSconfig array
     *
     * @deprecated Use FAL
     *
     * @return array An array with two keys, "value" and "properties" where
     *               "value" is a string with the value of the object string and
     *               "properties" is an array with the properties of the object
     *               string.
     */
    function _getTSConfigObject($objectString, $config)
    {
        $TSConf=array();
        $parts = explode('.', $objectString, 2);
        $key = $parts[0];
        if (trim($key)) {
            if (count($parts)>1 && trim($parts[1])) {
                // Go on, get the next level
                if (is_array($config[$key.'.'])) {
                    $TSConf = tx_dam_config::_getTSConfigObject(
                        $parts[1], $config[$key.'.']
                    );
                }
            } else {
                $TSConf['value']=$config[$key];
                $TSConf['properties']=$config[$key.'.'];
            }
        }
        return $TSConf;
    }

}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/dam/lib/class.tx_dam_config.php']) {
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/dam/lib/class.tx_dam_config.php']);
}
?>
