<?php
/**
 * Beursplein View for Beursplein 5 Component
 */

defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport( 'joomla.application.component.view');

/**
 * HTML View class for the Beursplein 5 Component
 */
class BeurspleinViewHome extends JView
{
  function display($tpl = null)
  {    
    //Get the user's stocks
    $userStockList = $this->get( 'StocksListTransformed', 'Portfolio' );    
    
    //Get the stock's names + images
    $list     = $this->get( 'StocksList', 'Stocks' );   
    //Transform it for better searching
    $stockList = array();
    foreach($list as $stock)
    {
      $stockList[$stock['id']] = $stock;
    }

    //Build the table
    $stocksTable = "<table border=\"1\">";
    $stocksTable .= "<tr><th>Soort</th><th>Verandering</th></tr>";
    
    foreach($userStockList as $stock)
    {
      $stockInfo = $stockList[$stock['stock_id']];
      
      $stocksTable .= "<tr><td>{$stock['amount']}x <img src=\"images/beursplein/{$stockInfo['image']}\" height=\"20\"  /> {$stockInfo['name']}</td><td>{$stockInfo['change']}</td></tr>";  
    }
    
    $stocksTable .= "</table>";
    $this->assignRef( 'stocksTable', $stocksTable );
    
    $money = $this->get( 'Money', 'Users' );
    $this->assignRef( 'money', $money );

    parent::display($tpl);
  }
}
?>
