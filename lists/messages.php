
<div style="float:right;">
	<?php echo $this->make_paging_links("{$this->db_pre}","id"); ?>
</div>

<?php $this->show_search_form(); ?>
<table cellspacing="0" width="100%" cellspadding="0" border="0" class="widefat">
<tbody id="the-list">
<tr class="alternate">
<td>Read/Reply</td>
<td><?php $this->make_order_links('From','name','False', "$op"); ?></td>
<td><?php $this->make_order_links('Date','date','True', "$op"); ?></td>
<td><?php $this->make_order_links('Email','email','False', "$op"); ?></td>
<td>Status</td>
<td>Delete</td>
</tr>

<?php
	$res = $this->get_messages();
	$i = 1;
	
	while ($result = mysql_fetch_assoc($res)) { 
		
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
<tr <?php if(!$this->is_odd($i)) { echo 'class="alternate"'; } ?>>
<td><a href="edit.php?page=contact_admin&edit=True&id=<?php echo $result['id']; ?><?php echo "{$order}{$order_direction}{$limit_start}"; ?>" />Read/Reply</a></td>
<td><?php echo $result['name']; ?></td>
<td><?php echo $this->format_date($result['date']); ?></td>
<td><?php echo $result['email']; ?></td>
<td><?php if ($result['date_read'] == "") { echo "Unread"; } else { echo "Read"; } ?></td>
<td><a href="edit.php?page=contact_admin&id=<?php echo $result['id']; ?>&delete=True">Delete</a>
</tr>
<?php $i++; } ?>
</tbody>
</table>
