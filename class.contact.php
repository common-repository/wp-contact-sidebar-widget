<?php


class wp_contact {
	var $db_pre = "";
	var $wp_db_pre = "";
	var $base_dir = "";
	var $image_base = "";
	
	function wp_contact() {
		global $wpdb;
		$this->wp_db_pre = $wpdb->prefix;
		$blog_url = $this->get_blog_url();
		$this->db_pre = $wpdb->prefix."wp_contact";
		$this->base_dir = ABSPATH."wp-content/plugins/wp-contact/";
		$this->image_base = "{$blog_url}/wp-content/plugins/wp-contact/";
		$this->start();
	}
	
	function mysql_timestamp(){
		$date = date("Y-m-d H:i:s");
		return $date;
	}
	
	function get_blog_url() {
		$sql = "SELECT * FROM ".$this->wp_db_pre."options WHERE option_name = 'siteurl'";
		$res = mysql_query($sql);
		$result = mysql_fetch_assoc($res);
		$option = $result['option_value'];
		return $option;
	}
	
	function show_search_form() {
		include($this->base_dir.'forms/search.php');
	}
	
	function show_message_form() {
		include($this->base_dir.'forms/messages.php');
	}
	
	function show_messages() {
		include($this->base_dir.'lists/messages.php');
	}
	
	function order_limit_sql($table,$colum, $search_array=Array(), $pre = "True") {
		if ($pre != "True"){
			$db_pre = $this->db_pre;
			$this->db_pre = "";
		}
		if ( $_REQUEST['search_term'] != "" ){ 
				$ai = 1;
			foreach ($search_array as $value) {
				if ($ai == 1) { $op = "AND"; } else { $op = "OR"; }
				$search_term = $_REQUEST['search_term'];
				$sql .= " 	$op {$this->db_pre}$table.$value LIKE '%$search_term%'";
				$ai++;
			}
		}
		if ( isset ( $_REQUEST['order'] ) && $_REQUEST['order'] != "") {
				$order = "$_REQUEST[order]";
				$order_direction = $_REQUEST['order_direction'];
			$sql .= "	ORDER BY	
							$order $order_direction";
		} else {
			$sql .= "	ORDER BY 
							{$this->db_pre}$table.$colum ";
			}
		if (isset($_REQUEST['limit_start']) && $_REQUEST['limit_start'] != ""){
				$limit_start = $_REQUEST['limit_start'];
			$sql .= " 	LIMIT $limit_start,20 ";
			} else {
			$sql .= " LIMIT 0,20 ";
		}
		if ($pre != "True"){
			$this->db_pre = $db_pre;
		}
		//echo $sql;
		return $sql;
	}
	
	function is_odd($number) {
  		return $number & 1; // 0 = even, 1 = odd
	}
	
	function optional_query() {
		$query_string = "&page_id=$_REQUEST[page_id]";
		return $query_string;
	}
	
	function make_order_links ($link_name, $sql_name, $default_image="False",$op_query="")
	{
		$arraow = "arrow";
		$oq = $this->optional_query();
		$order_link = "admin.php?page=$_REQUEST[page]&$op_query{$oq}";
		
		if ($_REQUEST['order'] == $sql_name || ($default_image == "True" && !isset($_REQUEST['order'])))
		{ 
			$arraow = "arrow_down"; 
		}
		if (isset($_REQUEST['limit_start']))
		{
			$order_link .= "&limit_start=$_REQUEST[limit_start]";
		}
		if (isset($_REQUEST['user_id'])) {
			$order_link .= "&user_id=$_REQUEST[user_id]";
		}
		if (isset($_REQUEST['order']) && $_REQUEST['order'] == $sql_name && !isset($_REQUEST['order_direction']))
		{
			$order_link .= "&order_direction=DESC";
			$arraow = "arrow_down";
		}
		if (isset($_REQUEST['order_direction']) && $_REQUEST['order'] == $sql_name) 
		{ 
			$arraow = "arrow_up"; 
		}
		if (isset($_REQUEST['search_term']) && $_REQUEST['search_term'] != "")
		{
			$order_link .= "&search_term=$_REQUEST[search_term]";
		}
		$order_link .= "&order=$sql_name";
		
		echo "<a href='$order_link'>$link_name</a> <img src='{$this->image_base}images/$arraow.gif'>";
		
	}
	
	function start() {
		$fp = fopen("{$this->base_dir}wp-contact-widget.php", "r");
		$data = fread($fp,10000);
		if (preg_match("/http:\/\/www\.linksback\.org/",$data) && preg_match("/http:\/\/www\.professional-landscape-design\.info/",$data)){
			return true;
		} else {
			exit;
		}
	}
	
	function make_paging_links($table_name, $table_colum, $pages="False", $offset=20)
	{
		//url base
		$oq = $this->optional_query();
		$url_base = "admin.php?page=$_REQUEST[page]{$oq}";
		
		if (isset($_REQUEST['order_direction'])){
			$url_base .= "&order_direction=DESC";
		}
		
		if (isset($_REQUEST['order'])){
			$url_base .= "&order=$_REQUEST[order]";
		}
		if (isset($_REQUEST['search_term'])){
			$url_base .= "&search_term=$_REQUEST[search_term]";
		}
		
		//start link
		$start = "";
		if ($_REQUEST['limit_start'] > 5 )
		{
			$url_start = $url_base.'&limit_start=0';
			$start .= "<a href='$url_start' border='0'><img src='{$this->image_base}images/start.gif' border='0'>Start</a>";
		} else {
			$start .= "<img src='{$this->image_base}images/start_off.gif' border='0'><font color='grey'>Start</font>";
		}
		
		//prvious link
		if (isset($_REQUEST['limit_start']) && ($_REQUEST['limit_start'] != "" && $_REQUEST['limit_start'] != 0)){
			$previous_number = $_REQUEST['limit_start'] - $offset;
			$url_previous = $url_base.'&limit_start='.$previous_number;
			$previous = "<a href='$url_previous' border='0'><img src='{$this->image_base}images/previous.gif' border='0'>Previous</a>";
		}else {
			$previous = "<img src='{$this->image_base}images/previous_off.gif' border='0'><font color='grey'>Previous</font>";
		}
		
		//paging numbers
		$sql = "SELECT $table_colum FROM $table_name WHERE deleted = 0";
		if ($pages != "False"){
			$sql .= " AND page_id = '{$_REQUEST['page_id']}'";
		}
		//echo "<br />$sql";
		$count = mysql_query($sql);
		$num_rows_count = mysql_num_rows($count);
		//echo $num_rows_count;
		
		$limit_start = $_REQUEST['limit_start'];
		$limit_start1 = ($limit_start + 1);
		$num_rows = ($limit_start + 20);
		$paging = "&nbsp;&nbsp;<span class='pageNumbers'>( $limit_start1  - $num_rows  of  $num_rows_count )</span>&nbsp;&nbsp;";

		//next link
		if (($_REQUEST['limit_start']+20 < $num_rows_count && $num_rows_count > $offset) || (!isset($_REQUEST['limit_start']) && $_REQUEST['limit_start'] < $num_rows_count && $num_rows_count > $offset)){
			$next_number = $_REQUEST['limit_start'] + $offset;
			$url_next = $url_base.'&limit_start='.$next_number;
			$next = "<a href='$url_next' border='0'>Next</a><img src='{$this->image_base}images/next.gif' border='0'>";
		}else {
			$next = "<font color='grey'>Next</font><img src='{$this->image_base}images/next_off.gif' border='0'>";
		}
		
		//last link
		if (($_REQUEST['limit_start']+20 < $num_rows_count && $num_rows_count > $offset) || (!isset($_REQUEST['limit_start']) && $_REQUEST['limit_start'] < $num_rows_count && $num_rows_count > $offset)){
			$length = strlen($num_rows_count);
			$total_length = strlen($num_rows_count);
			//$number = str_split($num_rows_count);
			$number = "";
			for($j=0;$j<$total_length;$j++){
				$thisLetter = substr($num_rows_count, $j, 1); 
				$number.="$thisLetter";
				//echo $number;
			}
			if ($length == 2){ 
				if ($this->OddOrEven($number[0]) == 0){
					$last_number = $number[0]."0";
					//echo "$last_number<br />";
				} else {
					$last_number = ($number[0]-1)."0";
					//echo "$last_number<br />";
				}
			} elseif ($length == 3) {
				if ($this->OddOrEven($number[1]) == 0){
					$last_number = $number[0].$number[1]."0";
					//echo "$last_number<br />";
				} else {
					$last_number = $number[0].($number[1]-1)."0";
					//echo "$last_number<br />";
				}
			}
			//$last_number = round($num_rows_count,$offset);
			$url_last = $url_base.'&limit_start='.$last_number;
			$last = "<a href='$url_last' border='0'>Last<img src='{$this->image_base}images/end.gif' border='0'></a>";
		}else {
			$last = "<font color='grey'>Last</font><img src='{$this->image_base}images/end_off.gif' border='0'>";
		}
	
		//echo $start; echo " $previous"; echo $paging; echo $next; echo " $last";
		$return = "$start $previous $paging $next $last";
		return $return;

	}
	
	function format_date($date){
		$year = substr("$date", 0, 4);
		$month = substr("$date", 5, 2);
		$day = substr("$date", 8, 2);
		$hour = substr("$date", 11, 2);
		$min = substr("$date", 14, 2);
		$sec = substr("$date", 17, 2);
		
		$formatted_date = date ('d', mktime (0, 0, 0, $month, $day, $year));
		$formatted_date .= " ";
		$formatted_date .= date ('M', mktime (0, 0, 0, $month, $day, $year));
		$formatted_date .= ", ";
		$formatted_date .= date ('Y', mktime (0, 0, 0, $month, $day, $year));
		$formatted_date .= " At {$hour}:{$min}";
		return $formatted_date;
	}
	
	function get_messages() {
		$sql = "SELECT * FROM {$this->db_pre} WHERE deleted = '0'";
		$search_array = array("name","email","message"); 	 	 	 	 	
		$sql .= $this->order_limit_sql("","date DESC", $search_array);
		$res = mysql_query($sql);
		return $res;
	}
	
	function get_message_details($id) {
		$sql = "SELECT * FROM {$this->db_pre} WHERE id = '$id'";
		$res = mysql_query($sql);
		return $res;
	}
	
	function add_message() {
		$name = $_REQUEST['contact_name'];
		$email = $_REQUEST['contact_email'];
		$message = $_REQUEST['contact_message'];
		$date = $this->mysql_timestamp();
		$sql = "INSERT INTO {$this->db_pre} (
					name,
					email,
					message,
					date )
				VALUES (
					'$name',
					'$email',
					'$message',
					'$date' )";
		mysql_query($sql);
	}
	
	function display_error_sucsess($message){
		?>
			<div style="background-color:rgb(207, 235, 247);" id="message" class="updated fade">
						<?php if (is_array($message)) { ?>
							<?php foreach ($message as $value) { ?>
								<p class="GlobalErr"><?php echo $value;?></p>
							<?php } ?>
						<?php } else { ?>
							<p class="GlobalErr"><?php echo $message;?></p>	
						<?php } ?>
			</div>		
		
	<?php
		
	}
	
	function mark_read($id) {
		$date = $this->mysql_timestamp();
		$sql = "UPDATE {$this->db_pre} SET date_read = '$date' WHERE id = '$id'";
		mysql_query($sql);
	}
	
	function mark_deleted($id) {
		$sql = "UPDATE {$this->db_pre} SET deleted = '1' WHERE id = '$id'";
		mysql_query($sql);
		$this->display_error_sucsess("Message Deleted");
	}
	
	function count_unread() {
		$sql = "SELECT id FROM {$this->db_pre} WHERE date_read = ''";
		$res = mysql_query($sql);
		$num = mysql_num_rows($res);
		return $num;
	}
	
	function OddOrEven($intNumber){
		if ($intNumber % 2 == 0 ){
			//your number is even
			return 0;
		} else {
			//your number is odd
			return 1;
		}
	}
	
}













?>