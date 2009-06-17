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
    
    $this->buildCardsTable($cardsTable);
    $this->assignRef( 'cardsTable', $cardsTable );
    
    $money = $this->get( 'Money', 'Users' );
    $this->assignRef( 'money', $money );
    
    parent::display($tpl);
  }
  
  function buildCardsTable(&$cardsTable)
  {
    $cardsTable = "";
    
    $cardsList = $this->get('UserCards', 'Cards');
    if(count($cardsList)==0)
    {
      $cardsTable  = "<form action=\"index.php?option=com_beursplein&amp;task=getcards\" ".
          "method=\"post\">\r\n";
      $cardsTable .= "\t<input type=\"submit\" name=\"getstocks\" value=\"Click hier om ".
          "kaarten uitegedeeld te krijgen\" />\r\n";
      $cardsTable .= "</form>\r\n";
      return;
    }
    
    $cardsTable = "<form action=\"index.php?option=com_beursplein&amp;task=selectcard\" method=\"post\">\r\n\r\n<table>\r\n";
    $counter    = 0;
    
    foreach($cardsList as $card)
    {
      if($counter%5==0)
        $cardsTable .= "\t<tr>\r\n";
      
      $images = explode(",", $card['images']);
      if(count($images)!=4)
        exit("Error, geen 4 images");

      $cardsTable .= "\t\t<td>\r\n\t\t\t<table border=\"1\">\r\n\t\t\t\t<tr>\r\n";
      $cardsTable .= "\t\t\t\t\t<td><img src=\"{$images[0]}\" alt=\"\" height=\"40\" /></td>\r\n";
      $cardsTable .= "\t\t\t\t\t<td><img src=\"{$images[1]}\" alt=\"\" height=\"40\" /></td>\r\n";
      $cardsTable .= "\t\t\t\t</tr>\r\n\t\t\t\t<tr>\r\n";      
      $cardsTable .= "\t\t\t\t\t<td><img src=\"{$images[2]}\" alt=\"\" height=\"40\" /></td>\r\n";
      $cardsTable .= "\t\t\t\t\t<td><img src=\"{$images[3]}\" alt=\"\" height=\"40\" /></td>\r\n";
      $cardsTable .= "\t\t\t\t</tr>\r\n\t\t\t\t<tr>\r\n\t\t\t\t\t";
      $cardsTable .= "<td><input type=\"radio\" name=\"card\" value=\"{$card['id']}\" /></td>\r\n";
      $cardsTable .= "\t\t\t\t</tr>\r\n\t\t\t</table>\r\n\t\t</td>\r\n";
      
      if($counter%5==4)
          $cardsTable .= "\t</tr>\r\n";
      
      $counter++;
    }
    $cardsTable .= "</table>\r\n<input type=\"submit\" value=\"Selecteer Kaart!\" />\r\n</form>\r\n";
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