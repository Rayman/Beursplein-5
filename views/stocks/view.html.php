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
    $stocksList  = $this->get( 'StocksList', 'Stocks' );    
    $totalStocks = $this->get( 'TotalStocks', 'Portfolio');
    
    $list = $this->get( 'StocksList', 'Portfolio');
    //Transform the userStocks
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
    
    //Build the table
    $stocksTable  = "<table border=\"1\" style=\"border-collapse: collapse;\">\r\n";
    $stocksTable .= "\t<tr>\r\n";
    $stocksTable .= "\t\t<th>Aandeel</th>\r\n";
    $stocksTable .= "\t\t<th>Waarde</th>\r\n";
    $stocksTable .= "\t\t<th>Verandering</th>\r\n";
    $stocksTable .= "\t\t<th>Gekocht</th>\r\n";
    $stocksTable .= "\t\t<th>Volume</th>\r\n";
    $stocksTable .= "\t\t<th>Actie</th>\r\n";
    $stocksTable .= "\t</tr>\r\n";
    
    $javascriptPriceList = "priceList = new Array();\n";
    
    foreach($stocksList as $stock)
    {
      $stocksTable .= "\t<tr>\r\n";
      
      //Aandeel
      $stocksTable .= "\t\t<td>\r\n\t\t\t<img src=\"./images/beursplein/{$stock['image']}\"
        height=\"30\" alt=\"{$stock['name']}\"/> \r\n";
      $stocksTable .= "\t\t\t".$stock['name']."\r\n\t\t</td>\r\n";
      
      //Waarde
      $stocksTable .= "\t\t<td>{$stock['value']}</td>\r\n";
      
      //Verandering      
      //Color
      if($stock['change']>0)
      {
        $color = "green";
      }
      elseif($stock['change']<0)
      {
        $color = "red";
      }
      else
      {
        $color = "blue";
      }
      
      //Change in %
      $changep = round(((float)$stock['change'])/((float)$stock['value'])*100);
      
      //Add the '+' sign
      $changep = $stock['change'] >= 0 ? "+".(string)$changep     : $changep;
      $change  = $stock['change'] >= 0 ? "+".(string)$stock['change'] : $stock['change'];
      
      //Draw it
      $stocksTable .= "\t\t<td><span style=\"color: $color\">{$change} ({$changep}%)</span></td>\r\n";
      
      //Gekocht
      $stock_bought = $userStocks[$stock['id']];
      $amount = isset($stock_bought) ? $stock_bought['amount'] : 0;
      $stocksTable .= "\t\t<td>{$amount}</td>\r\n";
      
      //Volume
      $stock_bought = $totalStocks[$stock['id']];
      $amount = isset($stock_bought) ? $stock_bought['amount'] : 0;
      $stocksTable .= "\t\t<td>{$amount}</td>\r\n";
      
      //Actie
      $stocksTable .= "\t\t<td>\r\n\t\t\t<input type=\"text\" size=\"10\"
        name=\"stock[{$stock['id']}]\" value=\"\" onchange=\"updateMoney(this)\" />\r\n";
      $stocksTable .= "\t\t\t<label><input type=\"radio\" name=\"options[{$stock['id']}]\"
        value=\"Buy\"  onchange=\"updateMoney()\" />Koop</label>\r\n";
      $stocksTable .= "\t\t\t<label><input type=\"radio\" name=\"options[{$stock['id']}]\"
        value=\"Sell\" onchange=\"updateMoney()\" />Verkoop</label>\r\n\t\t</td>\r\n";
      
      $stocksTable .= "\t</tr>\r\n";
      
      $javascriptPriceList .= "priceList['stock[{$stock['id']}]'] = {$stock['value']};\n";
    }
    
    $stocksTable .= "</table>";
    $this->assignRef( 'stocksTable', $stocksTable );
    
    $money = $this->get('Money', 'Users');
    $this->assignRef('money', $money);
    
    $this->assignRef('javascriptPriceList', $javascriptPriceList);

    parent::display($tpl);
  }
}
?>
