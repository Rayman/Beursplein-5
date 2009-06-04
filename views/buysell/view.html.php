<?php
/**
 * Beursplein View for Beursplein 5 Component
 */

defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport( 'joomla.application.component.view');

/**
 * HTML View class for the Beursplein 5 Component
 */
class BeurspleinViewBuysell extends JView
{
	function display($tpl = null)
	{		
		$stocksList = $this->get( 'StocksList', 'Stocks' );		
		
		//Build the table
		$stocksTable  = "<table border=\"1\" style=\"border-collapse: collapse;\">\r\n";
		$stocksTable .= "\t<tr>\r\n";
		$stocksTable .= "\t\t<th>Aandeel</th>\r\n";
		$stocksTable .= "\t\t<th>Verandering</th>\r\n";
		$stocksTable .= "\t\t<th>Gekocht</th>\r\n";
		$stocksTable .= "\t\t<th>Volume</th>\r\n";
		$stocksTable .= "\t\t<th>Actie</th>\r\n";
		$stocksTable .= "\t</tr>\r\n";
		
		foreach($stocksList as $stock)
		{
			$stocksTable .= "\t<tr>\r\n";
			
			//Aandeel
			$stocksTable .= "\t\t<td>\r\n\t\t\t<img src=\"./images/beursplein/{$stock['image']}\" height=\"30\" alt=\"{$stock['name']}\"/> \r\n";
			$stocksTable .= "\t\t\t".$stock['name']."\r\n\t\t</td>\r\n";
			
			//Verandering
			$stocksTable .= "\t\t<td>{$stock['change']}</td>\r\n";
			
			//Gekocht
			$stocksTable .= "\t\t<td>1</td>\r\n";
			
			//Volume
			$stocksTable .= "\t\t<td>1</td>\r\n";
			
			//Actie
			$stocksTable .= "\t\t<td><input type=\"button\" onclick=\"buy(this)\" name=\"{$stock['id']}}\" value=\"Koop\" />";
			$stocksTable .= "<input type=\"button\" onclick=\"alert('hoi')\" name=\"{$stock['id']}}\" value=\"Verkoop\" /></td>\r\n";
			
			$stocksTable .= "\t</tr>\r\n";
		}
		
		$stocksTable .= "</table>";
		$this->assignRef( 'stocksTable', $stocksTable );

		parent::display($tpl);
	}
}
?>
