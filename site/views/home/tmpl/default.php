<?php // no direct access
defined('_JEXEC') or die('Restricted access');?>

<h1>Welkom <?php echo $this->username;?> op het Beursplein 5</h1>

<?php
if(count($this->userStocks)==0)
{?>
<p>
  Je hebt nog geen aandelen, ga naar Aandelen om ze te kopen
</p>
<?php }
else
{?>
<p>
  Dit zijn je aandelen:
</p>
<table border="1" style="border-collapse: collapse;">
  <tr>
    <th>Soort</th>
    <th>Verandering</th>
  </tr>
<?php 
  $totalValue = 0;
  $stockList = $this->stockList;
  foreach($this->userStocks as $stock)
  {
    //get the stock info
    $stockInfo = $stockList[$stock['stock_id']];
    
    //Update totalValue
    $totalValue += $stockInfo['value']*$stock['amount'];
?>
  <tr>
    <td>
      <?php 
    echo $stock['amount'];
    echo ' x ';
    echo JHTML::Image($stockInfo['image'], $stockInfo['name'], Array('height'=>20));
?>

    </td>
<?php
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
    $changep = round(((float)$stockInfo['change']*100)/
        (((float)($stockInfo['value']) - $stockInfo['change'])));
    
    //Add the '+' sign
    $changep = $stockInfo['change'] >= 0 ? "+".(string)$changep             : $changep;
    $change  = $stockInfo['change'] >= 0 ? "+".(string)$stockInfo['change'] : $stockInfo['change'];
?>
    <td>
      <span style="color: <?php 
    echo $color;
    echo '">';
    echo $change;
    echo ' (';
    echo $changep;
    echo '%)';?>
</span>
    </td>    
  </tr>
<?php 
  }
?>
</table>
<?php
}
?>


<p>
  Totale waarde aandelen pakket: &euro;<?php echo $totalValue;?><br />
  Liquide middelen voor transactie: &euro;<?php echo $this->money;?>

</p>

<h2>Kaarten</h2>

<?php
//Get the user's stocks
$cardsList = $this->get('UserCards', 'Cards');

//On empty list, display a button
if(count($cardsList)==0)
{
  ?>
<form action="index.php?option=com_beursplein&amp;task=getcards" method="post">
  <input type="submit" name="getstocks" value="Klik hier om kaarten uitegedeeld te krijgen" />
</form>
<?php
}
else
{
  //Display the cards table
  ?>
<form action="index.php?option=com_beursplein&amp;task=selectcard" method="post">
  <table>
<?php
  $selectedCard = $this->get('SelectedCard', 'Users');
  $counter      = 0;
  $length       = count($cardsList);
  foreach($cardsList as $card)
  {
    //Begin of the table
    if($counter==0)
      echo "    <tr>\r\n";
    
    //Don't display played cards
    if($card['status'] != 'deck')
    {
      $length--;
      continue;
    }
    
    $images = explode(",", $card['images']);
    if(count($images)!=4)
      exit("Error, geen 4 images");
?>
      <td>
        <table <?php
    if($card['id'] == $selectedCard)
    {
      echo 'style="border: 3px solid"';
    }
    else
    {
      echo 'style="border: 2px dashed #C0C0C0"';
    }?> rules="none" frame="box">
          <tr align="center">
            <td><?php echo JHTML::image($images[0], "", array('height' => "40"));?></td>
            <td><?php echo JHTML::image($images[1], "", array('height' => "40"));?></td>
          </tr>
          <tr align="center">
            <td><?php echo JHTML::image($images[2], "", array('height' => "40"));?></td>
            <td><?php echo JHTML::image($images[3], "", array('height' => "40"));?></td>
          </tr>
          <tr align="center">
            <td colspan="2"><input type="radio" name="card" value="<?php
              echo $card['id'];echo '"';
              if($card['id']==$selectedCard) 
                echo ' checked="checked"';
              ?> /></td>
          </tr>
        </table>
      </td>
<?php
    //End of the table
    if($counter == $length - 1)
    {
      echo "    </tr>\r\n";
    }
    else
    {
      //Break the table at 5 cards
      if($counter%5==4)
      {
        echo "    </tr>\r\n    <tr>\r\n";
      }
    }
    $counter++;
  }
?>
  </table>
  <select name="stock">
<?php
    foreach($this->stockList as $stock)
    {
      echo '    <option value="';
      echo $stock['id']; echo '"';
      
      if($stock['id'] == $this->selectedStock)
      {
        echo ' selected="selected"';
      }
      
      echo '>';
      echo $stock['name'];
      echo "</option>\r\n";
    }
?>
  </select>
  <input type="submit" value="Update je Keuze!" />
</form>
<?php
}
?>

<h2>Handels log</h2>
<p>
ASDFDFDFSA
</p>