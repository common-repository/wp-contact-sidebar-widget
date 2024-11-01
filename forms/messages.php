<?php
	if($_REQUEST['edit'] == "True") {
		$res = $this->get_message_details($_REQUEST['id']);
		$result = mysql_fetch_assoc($res);
	}
?>
<table>
	<tr>
		<td align="right"><b>Back To Messages:</b></td>
        <?php
		if (isset($_REQUEST['order'])){
			$order = "&order=$_REQUEST[order]";
		}
		if (isset($_REQUEST['limit_start'])){
			$limit_start = "&limit_start=$_REQUEST[limit_start]";
		}
		if (isset($_REQUEST['order_direction'])){
			$order_direction = "&order_direction=$_REQUEST[order_direction]";
		}
		?>
        <td align="left"> <a href="admin.php?page=contact_admin<?php echo "{$order}{$order_direction}{$limit_start}"; ?>">Go Back</a></td>
   </tr>
   <tr>
		<td align="right">&nbsp;</td>
        <td align="left">&nbsp;</td>
   </tr>
	<tr>
		<td align="right"><b>Name:</b></td>
        <td align="left"> <?php echo $result['name']; ?></td>
   </tr>
   <tr>
		<td align="right"><b>Email:</b></td>
        <td align="left"> <?php echo $result['email']; ?></td>
   </tr>
   <tr>
		<td align="right"><b>Message:</b></td>
        <td align="left"> <?php echo $result['message']; ?></td>
   </tr>
</table><br />
<h2> Reply </h2>
<form action="edit.php?page=contact_admin" method="post" name="contact" id="contact">
  <table>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">To:</td>
      <td><input type="text" name="email" value="<?php echo $result['email']; ?>" size="32" /></td>
    </tr>  
     <tr valign="baseline">
      <td nowrap="nowrap" align="right">Subject:</td>
      <td><input type="text" name="subject" value="" size="32" /></td>
    </tr>    
    <tr valign="baseline">
      <td nowrap="nowrap" align="right" valign="top">Message:</td>
      <td><textarea name="message" cols="50" rows="5"></textarea>      </td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Submit" /></td>
    </tr>
  </table>
  <input type="hidden" name="send_message" value="True" />
</form>
