<?php
/**
 * Beursplein Model for Beursplein 5 Component
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );

/**
 * Beursplein Model
 */
class BeurspleinModelBeursplein extends JModel
{
	/**
	 * Gets the stocks
	 * @return array with stocks + values
	 */
	function getStocksList()
	{
		$db =& JFactory::getDBO();

		$query = 'SELECT * FROM #__beursplein_stocks';
		$db->setQuery( $query );
		$list = $db->loadAssocList();

		return $list;
	}
}
