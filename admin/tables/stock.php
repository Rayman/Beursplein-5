<?php
/**
 * Beursplein 5 table class
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * Stock Table class
 */
class TableStock extends JTable
{
	/**
	 * Primary Key
	 *
	 * @var int
	 */
	var $id = null;

	/**
	 * @var string
	 */
	var $name = null;
	
  /**
	 * @var string
	 */
	var $image = null;

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function TableStock(& $db) {
		parent::__construct('#__beursplein_stocks', 'id', $db);
	}
}

