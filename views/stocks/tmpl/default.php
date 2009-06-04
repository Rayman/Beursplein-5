<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>

<h1>Huidige Koerse</h1>

<form id="stockForm"
  action="index.php?option=com_beursplein&amp;view=stocks&amp;task=buysell"
  method="post"
  onsubmit="validateInput()">

<?php echo $this->stocksTable;?>

  <input type="submit" value="Koop &amp; Verkoop" onclick="return validateInput()" />
  <p>
     Geld voor transactie: <?php echo $this->money;?><br />
     Geld na Transactie: <span id="money" style="color: red">Loading...</span>
  </p>
</form>

<script type="text/javascript"><!--//--><![CDATA[//><!--
//Holds the money, DONT ALTER IT
money = <?php echo $this->money;?>;
document.getElementById('money').innerHTML = money;

<?php echo $this->javascriptPriceList;?>;

/*
* Search for an option with name
* @return true if 'Buy', false if 'sell', null on error
*/
function getOption(name)
{
  var inputName = 'options'+name.substring(5);    
  
  //Search for a radio button with name == inputName
  for(var i=0; i < document.getElementById('stockForm').length; i++)
  {
    element = document.getElementById('stockForm')[i];
    
    if(element.type == 'radio' && element.name == inputName)
    {      
      if(element.value == "Buy" && element.checked)
      {
	return true;
      }
      else if(element.value == "Sell" && element.checked)
      {
	return false;
      }
    }
  }
  
  //Error, so return null
  return null;
}

function updateMoney(inputElement)
{
  //Validate the input in the text box
  if(inputElement!=null)
  {  
    var value = Math.round(inputElement.value,0);
    value     = isNaN(value) ? 0 : value;
    value     = value < 0    ? 0 : value;
    inputElement.value = value+"";
  }
  
  //Count all money
  var sum = 0;
  for(var i=0; i < document.getElementById('stockForm').length; i++)
  {
    element = document.getElementById('stockForm')[i];
    
    if(element.type == 'text')
    {
      var amount = parseInt(element.value,10);
      if(amount==0||isNaN(amount))
	continue;
      var name   = element.name;
      var price  = priceList[name];
      var cost   = amount * price;
      var buy    = getOption(name);
      if(buy==null)
      {
	continue;      
      }
      sum += buy ? -cost : cost;
    }
  }
  document.getElementById('money').innerHTML = money + sum;
}

function validateInput()
{ 
  //Update the money
  updateMoney();
  
  //Check for inputs not set
  for(var i=0; i < document.getElementById('stockForm').length; i++)
  {
    if(document.getElementById('stockForm')[i].type == 'text')
    {
      //If the amount is set and not zero
      var amount = parseInt(document.getElementById('stockForm')[i].value,10);
      if(amount==0||isNaN(amount))
	continue;
      
      //Check if the option is set
      var name = document.getElementById('stockForm')[i].name;
      var buy = getOption(name);
      if(buy==null)
      {
	alert("Je hebt een optie niet aangevinkt!");
	return false;
      }
    }
  }
  
  //Money checkML);
  if(parseInt(document.getElementById('money').innerHTML,10)<0)
  {
    alert("Je hebt niet genoeg geld om die aandelen te kopen!");
    return false;
  }  
  
  //Everything OK
  return true;
}
//--><!]]></script>
