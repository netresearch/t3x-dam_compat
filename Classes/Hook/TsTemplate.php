<?php

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

/**
 * Enforce inclusion of our static setup right after the css_styled_content templates
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
class TsTemplate
{
    /**
     * Enforce inclusion of our static setup right after the css_styled_content
     * static templates
     * 
     * @param array $params
     * 
     * @return void
     */
    public function includeStatic(&$params)
    {
        if ($params['row']['include_static_file']) {
            $params['row']['include_static_file'] = preg_replace(
                '#(EXT:css_styled_content/static/[^,]*)#',
                '$1,EXT:dam/static/',
                $params['row']['include_static_file']
            );
        }
    }
}
?>
