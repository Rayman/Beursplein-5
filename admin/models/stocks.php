<?php
/**
 * Stocks Model for Beursplein 5 Component
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.model' );

/**
 * Stocks Model
 */
class BeurspleinsModelStocks extends JModel
{
  /**
   * Hellos data array
   *
   * @var array
   */
  var $_data;


  /**
   * Returns the query
   * @return string The query to be used to retrieve the rows from the database
   */
  function _buildQuery()
  {
    $query = ' SELECT * '
      . ' FROM #__beursplein_stocks'
    ;

    return $query;
  }

  /**
   * Retrieves the Stocks data
   * @return array Array of objects containing the data from the database
   */
  function getData()
  {
    // Lets load the data if it doesn't already exist
    if (empty( $this->_data ))
    {
      $query = $this->_buildQuery();
      $this->_data = $this->_getList( $query );
    }

    return $this->_data;
  }
  
  function reset()
  {
    $db = $this->_db;
    $q = "SELECT *
          FROM ".$db->nameQuote('#__beursplein_stocks');
    $db->setQuery($q);
    echo $db->getQuery()."<br />";
    $list = $db->loadAssocList();
    foreach($list as $item)
    {
      $entry          = new stdClass();
      $entry->id      = $item['id'];
      $entry->value   = 150;
      $entry->change  = 0;
      $entry->speed   = 0;
      $entry->growing = 'true';
      
      $result = $db->updateObject('#__beursplein_stocks', $entry, 'id');
      echo $db->getQuery();
      
      if($result)
      {
        echo " <b>OK</b><br />";
      }   
      else
      {
        return false;
      }
    }    
    return true;
  }
}

