<?php
// Author: Fredrik Åhman
// Course: PHPMVC @ BTH
// File: IHasSQL.php
// Desc: Interface to encapsulate interaction with database

/**
 * Interface IHasSQL
 */
interface IHasSQL{

	/**
	 * Public function SQL
	 * Parameters: key
	 */
	public static function SQL($key=null);
}

?>