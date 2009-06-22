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
    $userStocks = $this->get('StocksListTransformed', 'Portfolio');
    
    //Get the stock's names + images
    $list = $this->get( 'StocksList', 'Stocks' );
    //Transform it for better searching
    $stockList = array();
    foreach($list as $stock)
    {
      $stockList[$stock['id']] = $stock;
    }
    
    $this->assignRef('userStocks', $userStocks);
    $this->assignRef('stockList',  $stockList);
    
    //Get the money 
    $money = $this->get( 'Money', 'Users' );
    $this->assignRef( 'money', $money );
    
    parent::display($tpl);
  }
  
  function buildStocksTable(&$stocksTable, &$totalValue)
  {

    
    
  }
}