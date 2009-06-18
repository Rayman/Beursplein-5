<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>
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
<?php echo $this->cardsTable;?>


