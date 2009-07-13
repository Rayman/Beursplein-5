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
}