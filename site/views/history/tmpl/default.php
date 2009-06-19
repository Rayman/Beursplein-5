<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>

<h1>Geschiedenis van KOERSJE</h1>

<table
    style="height: 200px; background-color: white"
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
          <td style="height: <?php echo $row['value'];?>px; background-color: red"></td>
        </tr>
      </table>
    </td>
<?php
}?>
  </tr>
</table>