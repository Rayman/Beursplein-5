<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<h1>Welkom <?php echo $this->username;?> op het Beursplein 5</h1>

<p>
  Dit zijn je aandelen:
  <?php echo $this->stocksTable;?>
  Totale waarde aandelen pakket: <?php echo $this->totalValue;?><br />
  Liquide middelen voor transactie: <?php echo $this->money;?>
</p>
<h2>Handels log</h2>
<p>
  <b>**** 03 mei 2009 21:56 ****</b><br />
  Buy:   Dommelsch nv   #2   @   &euro;46 <br />
  Buy:   J.Wilbers Bergsport   #9   @   	&euro;10<br />
  Buy:   van Delluf Audio Solutions   #9   @    	&euro;10<br /><br />
  
  <b>**** 03 mei 2009 21:53 ****</b><br />
  User started game <br />
</p>



