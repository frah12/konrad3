<?php
// Author: Fredrik Åhman
// Course: PHPMVC @ BTH
// File: IModule.php
// Desc: Interface for class that can be installed, uninstalled, or updated.

/**
 * Interface IModule
 */
interface IModule{

	/**
	 * Public function Manage
	 * Parameters: action
	 */
	public function Manage($action=null);
}

?>