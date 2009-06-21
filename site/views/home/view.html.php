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
    $this->buildStocksTable($stocksTable, $totalValue);
    $this->assignRef( 'stocksTable', $stocksTable );
    $this->assignRef( 'totalValue', $totalValue);
    
    $money = $this->get( 'Money', 'Users' );
    $this->assignRef( 'money', $money );
    
    parent::display($tpl);
  }
  
  function buildStocksTable(&$stocksTable, &$totalValue)
  {
    //Get the user's stocks
    $userStockList = $this->get( 'StocksListTransformed', 'Portfolio' );
    
    //Get the stock's names + images
    $list = $this->get( 'StocksList', 'Stocks' );
    
    //Transform it for better searching
    $stockList = array();
    foreach($list as $stock)
    {
      $stockList[$stock['id']] = $stock;
    }
    
    //Build the table
    $stocksTable  = "<table border=\"1\" style=\"border-collapse: collapse;\">\r\n";
    $stocksTable .= "\t<tr>\r\n\t\t<th>Soort</th>\r\n\t\t<th>Verandering</th>\r\n\t</tr>\r\n";
    
    $totalValue = 0;
    
    foreach($userStockList as $stock)
    {
      //get the stock info
      $stockInfo = $stockList[$stock['stock_id']];
      
      //Update totalValue
      $totalValue += $stockInfo['value']*$stock['amount'];
      
      //First the amount x icon + name
      $stocksTable .= "\t<tr>\r\n\t\t<td>{$stock['amount']} x ".
        "<img src=\"{$stockInfo['image']}\" ".
        "height=\"20\" alt=\"images/beursplein/{$stockInfo['image']}\" />".
        "{$stockInfo['name']}</td>\r\n";
      
      //Verandering      
      //Color
      if($stockInfo['change']>0)
      {
        $color = "green";
      }
      elseif($stockInfo['change']<0)
      {
        $color = "red";
      }
      else
      {
        $color = "blue";
      }
      
      //Change in %
      $changep = round(((float)$stockInfo['change'])/((float)$stockInfo['value'])*100);
      
      //Add the '+' sign
      $changep = $stockInfo['change'] >= 0 ? "+".(string)$changep             : $changep;
      $change  = $stockInfo['change'] >= 0 ? "+".(string)$stockInfo['change'] : $stockInfo['change'];
      
      //Draw it
      $stocksTable .= "\t\t<td><span style=\"color: $color\">{$change} ({$changep}%)".
        "</span></td>\r\n\t</tr>\r\n";
    }
    
    $stocksTable .= "</table>\r\n"; 
  }
}