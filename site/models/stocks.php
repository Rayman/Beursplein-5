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
class BeurspleinModelStocks extends JModel
{
  /**
   * Gets the stocks
   * @return array with stocks + values
   */
  function getStocksList()
  {
    $db =& JFactory::getDBO();

    $query = "SELECT * FROM ".$db->nameQuote('#__beursplein_stocks');
    $db->setQuery( $query );
    $list = $db->loadAssocList();

    return $list;
  }
  
  /**
   * Get all the stocks with the prices
   * @return array with $stock_id => $price
   */
  function getPriceList()
  {    
    $db =& JFactory::getDBO();
    
    //Get the prices
    $query = "SELECT ".$db->nameQuote('id').", ".$db->nameQuote('value')." 
              FROM ".$db->nameQuote('#__beursplein_stocks');
    $db->setQuery( $query );
    $db->query();
    $result = $db->loadAssocList();
    
    //Transform result to stocksList
    $stockList = array();
    foreach($result as $stock)
    {
      $stockList[$stock['id']] = $stock['value'];
    }
    
    return $stockList;
  }
  
  function getStock($id)
  {
    $db =& JFactory::getDBO();
    
    //Get the prices
    $query = "SELECT * 
              FROM  ".$db->nameQuote('#__beursplein_stocks')." 
              WHERE ".$db->nameQuote('id')." = ".$db->quote($id);
    $db->setQuery( $query );
    $db->query();
    return $db->loadAssoc();
  }
}