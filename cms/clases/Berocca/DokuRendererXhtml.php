<?php
/**
 * Renderer for XHTML output
 *
 * @author Harry Fuecks <hfuecks@gmail.com>
 * @author Andreas Gohr <andi@splitbrain.org>
 */

if(!defined('DOKU_INC')) define('DOKU_INC',realpath(dirname(__FILE__).'/../../').'/');

if ( !defined('DOKU_LF') ) {
    // Some whitespace to help View > Source
    define ('DOKU_LF',"\n");
}

if ( !defined('DOKU_TAB') ) {
    // Some whitespace to help View > Source
    define ('DOKU_TAB',"\t");
}

require_once DOKU_INC . 'inc/parser/renderer.php';
require_once DOKU_INC . 'inc/html.php';

/**
 * The Renderer
 */
class Berocca_DokuRendererXhtml extends Doku_Renderer_Xhtml {

    function listitem_open($level) {
        $this->doc .= '<li class="level'.$level.'"><span>';
    }

    function listitem_close() {
        $this->doc .= '</span></li>'.DOKU_LF;
    }

}
