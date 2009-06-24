<?php // no direct access
defined('_JEXEC') or die('Restricted access');?>
    <ul style="text-align:center;
    padding-bottom:5px;
    padding-top:5px;
    padding-left:0;
    margin-top:0; /* cancels gap caused by top padding in Opera 7.54 */
        margin-left:0;
    background-color:#036;
    color:#fff;
    width:100%;
    line-height:18px; /* fixes Firefox 0.9.3 */
        ">
<?php
foreach($this->stockList as $stock)
{?>
    <li style="	display:inline;
    padding-left:10px;
    padding-right:10px;
    padding-bottom:5px;
    padding-top:5px;
    border-right:1px solid #fff;
    "><?php echo $stock['name'];?></li>
    
        <?php } ?>
    
</ul>
    
    
    
<h1>Geschiedenis van KOERSJE</h1>

<table
    style="height: 200px; background-color: white"
    width="100"
    cellspacing="0"
    cellpadding="0"
    border="0">
  <tr>
<?php
if(isset($this->history))
{
  foreach($this->history as $row)
  {?>
    <td valign="bottom">
      <table style="height: 100%;" width="10px" cellspacing="0" cellpadding="0" border="0">
        <tr>
          <td></td>
        </tr>
        <tr>
          <td style="height: <?php echo $row['value'];?>px; background-color: red"></td>
        </tr>
      </table>
    </td>
<?php
  }?>
  </tr>
</table>
<?php
}
?>