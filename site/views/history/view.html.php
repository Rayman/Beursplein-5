<?php
/**
 * Beursplein View for Beursplein 5 Component
 */

defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport( 'joomla.application.component.view');

/**
 * HTML View class for the Beursplein 5 Component
 */
class BeurspleinViewHistory extends JView
{
  function display($tpl = null)
  { 
    
    $stock_id = JRequest::getInt('stock', 0, 'get');
    if($stock_id != 0)
    {
      $historyModel = $this->getModel('History');
      $history = $historyModel->getHistory($stock_id);
      $this->assignRef('history', $history);
    }
    
    $stockList = $this->get('StocksList', 'Stocks');
    $this->assignRef('stockList', $stockList); 
    
    parent::display($tpl);
  }
}
?>
