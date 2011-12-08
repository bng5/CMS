<?php

/**
 * Description of Admin
 *
 * @author pablo
 */
abstract class View_Admin {

    protected $_children;

    public function  __construct() {
        $this->_children = new SplObjectStorage();
    }

    public function appendChild(View_Admin $child) {
		$child->parent = $this;
		if(!$this->_children) {
			$this->_children = new SplObjectStorage;
		}
		$this->_children->attach($child);
		return $child;
	}
    
    abstract public function show();

    public function showChildren() {
        if($this->_children && $this->_children->count()) {
			foreach($this->_children AS $child) {
				$child->show();
            }
		}
    }

}
