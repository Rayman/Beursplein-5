<?php // no direct access
defined('_JEXEC') or die('Restricted access');?>

<h1>Welkom <?php echo $this->username;?> op het Beursplein 5</h1>

<p>
  Dit zijn je aandelen:
</p>
  <?php echo $this->stocksTable;?>
<p>
  Totale waarde aandelen pakket: &euro;<?php echo $this->totalValue;?><br />
  Liquide middelen voor transactie: &euro;<?php echo $this->money;?>
</p>
<h2>Handels log</h2>
<p>
  ASDFDFDFSA
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
  <input type="submit" name="getstocks" value="Klik hier om kaarten uitegedeeld te krijgen" />";
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
  $counter    = 0;
  foreach($cardsList as $card)
  {
    if($counter==0)
      echo "    <tr>\r\n";
    
    $images = explode(",", $card['images']);
    if(count($images)!=4)
      exit("Error, geen 4 images");
?>
      <td>
        <table border="1"<?php
          if($card['id']==$selectedCard)
          {
            ?> style="background: yellow;"<?php
          }
          ?>>
          <tr>
            <td><img src="<?php echo $images[0];?>" alt="" height="40" /></td>
            <td><img src="<?php echo $images[1];?>" alt="" height="40" /></td>
          </tr>
          <tr>
            <td><img src="<?php echo $images[2];?>" alt="" height="40" /></td>
            <td><img src="<?php echo $images[3];?>" alt="" height="40" /></td>
          </tr>
          <tr>
            <td><input type="radio" name="card" value="<?php
              echo $card['id'];echo '"';
              if($card['id']==$selectedCard) 
                echo ' checked="checked"';
              ?> /></td>  
          </tr>
        </table>
      </td>
<?php
    //End of the table
    if($counter == sizeof($cardsList)-1)
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
  <input type="submit" value="Selecteer Kaart!" />
</form>
<?php
}
?>