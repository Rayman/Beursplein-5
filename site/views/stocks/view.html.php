<?php
/**
 * Beursplein View for Beursplein 5 Component
 */

defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport( 'joomla.application.component.view');

/**
 * HTML View class for the Beursplein 5 Component
 */
class BeurspleinViewStocks extends JView
{
  function display($tpl = null)
  {
    //Get the stocks
    $stocksList  = $this->get( 'StocksList', 'Stocks' );
    $this->assignRef('stocksList', $stocksList);
    
    $javascriptPriceList = "";
    foreach($stocksList as $stock)
    {
      $javascriptPriceList .= "priceList['stock[{$stock['id']}]'] = {$stock['value']};\n";
    }
    $this->assignRef('javascriptPriceList', $javascriptPriceList);
      
    
    //Get all bought stocks
    $totalStocks = $this->get( 'TotalStocks', 'Portfolio');
    $this->assignRef('totalStocks', $totalStocks);
    
    //Get the users stocks
    $list = $this->get( 'StocksList', 'Portfolio');
    
    //TODO:
    //Combine these, so only 1 query has to be done
    
    //Add amounts together
    $userStocks = array();
    foreach($list as $stock)
    {
      if(isset($userStocks[$stock['stock_id']]))
      {
	$userStocks[$stock['stock_id']]['amount'] += $stock['amount'];
      }
      else
      {
	$userStocks[$stock['stock_id']]['amount'] = $stock['amount'];
      }
    }    
    $this->assignRef('userStocks', $userStocks);
     
    //Get the user's money
    $money = $this->get('Money', 'Users');
    $this->assignRef('money', $money);
    
    parent::display($tpl);
  }
}
?>
