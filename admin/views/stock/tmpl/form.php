<?php defined('_JEXEC') or die('Restricted access'); ?>

<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="col100">
  <fieldset class="adminform">
    <legend><?php echo JText::_( 'Details' ); ?></legend>

    <table class="admintable">
    <tr>
      <td width="100" align="right" class="key">
        <label for="name">
          <?php echo JText::_( 'Stock' ); ?>:
        </label>
      </td>
      <td>
        <input class="text_area"
               type="text"
               name="name"
               id="name"
               size="32"
               value="<?php echo $this->stock->name;?>" />
      </td>
      <td>
        <input class="text_area"
               type="text"
               name="image"
               id="image"
               size="64"
               value="<?php echo $this->stock->image;?>" />
      </td>
      <td>
        <input type="button" onclick="updateImage()" value="Preview" />
      </td>
    </tr>
  </table>
  </fieldset>
</div>
<div class="col100" id="imageContainer"></div>
<div class="clr"></div>

<input type="hidden" name="option" value="com_beursplein" />
<input type="hidden" name="id" value="<?php echo $this->stock->id; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="controller" value="stocks" />
</form>
<script type="text/javascript">
// <![CDATA[
function updateImage()
{
  var url = document.getElementById('image').value;
  var element = document.getElementById('imageContainer');
  element.innerHTML = 'Image: <img src="../'+url+'" alt="" />';
}
// ]]>
</script>

