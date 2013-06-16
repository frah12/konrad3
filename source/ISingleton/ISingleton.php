<?php
// Author: Fredrik Åhman
// Course: PHPMVC @ BTH
// File: ISingleton.php
// Desc: Interface for classes using singleton pattern
// Uses singleton pattern==Ser till att endast ett objekt instansieras

/**
 * Interface ISingleton
 */

interface ISingleton{

	/**
	 * Public static function Instance
	 */
	public static function Instance();
}

?>