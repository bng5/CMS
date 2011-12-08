<?php
/**
 * DokuWiki Events
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Christopher Smith <chris@jalakai.co.uk>
 */

if(!defined('DOKU_INC'))
    define('DOKU_INC',realpath(dirname(__FILE__).'/../').'/');
require_once(DOKU_INC.'inc/pluginutils.php');


/**
 *  trigger_event
 *
 *  function wrapper to process (create, trigger and destroy) an event
 *
 *  @PARAM  $name               (string)   name for the event
 *  @PARAM  $data               (mixed)    event data
 *  @PARAM  $action             (callback) (optional, default=NULL) default action, a php callback function
 *  @PARAM  $canPreventDefault  (bool)     (optional, default=true) can hooks prevent the default action
 *
 *  @RETURN (mixed)                        the event results value after all event processing is complete
 *                                         by default this is the return value of the default action however
 *                                         it can be set or modified by event handler hooks
 */
function trigger_event($name, &$data, $action=NULL, $canPreventDefault=true) {

  $evt = new Doku_Event($name, $data);
  return $evt->trigger($action, $canPreventDefault);
}

// create the event handler
global $EVENT_HANDLER;
$EVENT_HANDLER = new Doku_Event_Handler();
