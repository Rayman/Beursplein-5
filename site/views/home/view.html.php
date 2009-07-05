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
    $this->assignRef('userStocks', $userStocks);
    
    //Get the stock's names + images
    $stockList = $this->get( 'StocksListTransformed', 'Stocks' );
    $this->assignRef('stockList',  $stockList);
    
    //Get the money 
    $money = $this->get( 'Money', 'Users' );
    $this->assignRef( 'money', $money );
    
    $selectedStock = $this->get('SelectedStock', 'Users');
    $this->assignRef('selectedStock', $selectedStock);
    
    parent::display($tpl);
  }
}