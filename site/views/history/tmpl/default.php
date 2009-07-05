<?php // no direct access
defined('_JEXEC') or die('Restricted access');?>
    <ul>
<?php
foreach($this->stockList as $stock)
{?>
    <li><a href="index.php?option=com_beursplein&amp;view=history&amp;stock=<?php 
        echo $stock['id'];?>"><?php echo $stock['name'];?></a></li>
    
        <?php } ?>
    
</ul>

<?php
if(isset($this->history))
{?> 
    
<h1>Geschiedenis van <?php echo $this->stockName;?></h1>

<h2>Waarde</h2>

<table
    style="height: 300px; background-color: #55FF55"
    width="100"
    cellspacing="0"
    cellpadding="0"
    border="0">
  <tr>
<?php
foreach($this->history as $row)
  {?>
    <td valign="bottom">
      <table style="height: 100%;" width="10px" cellspacing="0" cellpadding="0" border="0">
        <tr>
          <td></td>
        </tr>
        <tr>
          <td style="height: <?php echo $row['value'];?>px; background-color: green"></td>
        </tr>
      </table>
    </td>
<?php
  }?>
  </tr>
</table>
<h2>Volume</h2>
<table
    style="height: 300px; background-color: #55FF55"
    width="100"
    cellspacing="0"
    cellpadding="0"
    border="0">
  <tr>
<?php
{
  $maxVolume = 0;
  foreach($this->history as $row)
  {
    $maxVolume = max($maxVolume, $row['volume']);
  }
  
  foreach($this->history as $row)
  {?>
    <td valign="bottom">
      <table style="height: 100%;" width="10px" cellspacing="0" cellpadding="0" border="0">
        <tr>
          <td></td>
        </tr>
        <tr>
          <?php
          if($maxVolume == 0)
          {
            echo "<td></td>";
          }
          else
          {            
            $height = $row['volume'] * 300 / $maxVolume;
            if($height == 0)
            {
              echo "<td></td>";
            }
            else
            {?> 
          <td style="height: <?php echo $height?>px; background-color: green"></td>
          <?php } }?>
            
        </tr>
      </table>
    </td>
<?php }?>
  </tr>
</table>
<?php
  }
}
?>