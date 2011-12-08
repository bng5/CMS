<?php

class Doku_Event_Handler {

  // public properties:  none

  // private properties
  var $_hooks = array();          // array of events and their registered handlers

  /**
   * event_handler
   *
   * constructor, loads all action plugins and calls their register() method giving them
   * an opportunity to register any hooks they require
   */
  function Doku_Event_Handler() {

    // load action plugins
    $plugin = NULL;
    $pluginlist = plugin_list('action');

    foreach ($pluginlist as $plugin_name) {
      $plugin =& plugin_load('action',$plugin_name);

      if ($plugin !== NULL) $plugin->register($this);
    }
  }

  /**
   * register_hook
   *
   * register a hook for an event
   *
   * @PARAM  $event   (string)   name used by the event, (incl '_before' or '_after' for triggers)
   * @PARAM  $obj     (obj)      object in whose scope method is to be executed,
   *                             if NULL, method is assumed to be a globally available function
   * @PARAM  $method  (function) event handler function
   * @PARAM  $param   (mixed)    data passed to the event handler
   */
  function register_hook($event, $advise, &$obj, $method, $param=NULL) {
    $this->_hooks[$event.'_'.$advise][] = array(&$obj, $method, $param);
  }

  function process_event(&$event,$advise='') {

    $evt_name = $event->name . ($advise ? '_'.$advise : '_BEFORE');

    if (!empty($this->_hooks[$evt_name])) {
      $hook = reset($this->_hooks[$evt_name]);
      do {
//        list($obj, $method, $param) = $hook;
        $obj =& $hook[0];
        $method = $hook[1];
        $param = $hook[2];

        if (is_null($obj)) {
          $method($event, $param);
        } else {
          $obj->$method($event, $param);
        }

      } while ($event->_continue && $hook = next($this->_hooks[$evt_name]));
    }
  }
}