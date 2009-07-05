<?php // no direct access
defined('_JEXEC') or die('Restricted access');?>

<h1>Hallo nieuwe gebruiker</h1>

<p>Je bent hier voor het eerst. Als je mee wilt doen aan beursplein 5, klik dan hieronder om mee te doen.</p>

<form action="index.php" method="get">
  <input type="submit" value="Ik doe mee!" />
  <input type="hidden" name="option" value="com_beursplein" />
  <input type="hidden" name="task"   value="register" />
</form>