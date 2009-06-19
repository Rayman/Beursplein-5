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
    $historyModel = $this->getModel('History');    
    $stock_id = JRequest::getInt('stock', 0, 'get');    
    $history = $historyModel->getHistory($stock_id);
    
    $this->assignRef('history', $history);
    
    parent::display($tpl);
  }
}
?>
