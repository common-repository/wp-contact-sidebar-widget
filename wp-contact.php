<?php
/*
Plugin Name:WP-Contact
Plugin URI: http://www.linksback.org
Description: Adds a contact form to your side bar!
Version: 1.0
Author: Eric Medlin of Digital Studio LLC
Author URI: http://www.linksback.org
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
*/ 


require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
global $wpdb , $wp_roles;	

function wp_contact_install () {
	global $wpdb;
	$contact_db = "CREATE TABLE `".$wpdb->prefix."wp_contact` (
			  	`id` int(10) NOT NULL auto_increment,
				  `name` varchar(255) NOT NULL,
				  `email` varchar(255) NOT NULL,
				  `message` mediumtext NOT NULL,
				  `date` varchar(40) NOT NULL,
				  `deleted` tinyint(4) NOT NULL default '0',
				  `date_read` varchar(40) NOT NULL,
				  PRIMARY KEY  (`id`)
				) TYPE=MyISAM ;";

maybe_create_table(($wpdb->prefix."wp_contact"),$contact_db);  
}

if (isset($_GET['activate']) && $_GET['activate'] == 'true') {
   add_action('init', 'wp_contact_install');
}

function contact_admin() {
	require_once('../wp-content/plugins/wp-contact/class.contact.php');
	$con = new wp_contact();
	if ($_REQUEST['send_message'] == "True") {
		$to = $_REQUEST['email'];
		$subject = $_REQUEST['subject'];
		$message = $_REQUEST['message'];
		wp_mail($to,$subject,$message);
		$con->display_error_sucsess("Message Sent To $to");
	}
	if ($_REQUEST['delete'] == "True") {
		$con->mark_deleted($_REQUEST['id']);
	}
	echo "<div class='wrap'><h2>Contact</h2>";
	if ($_REQUEST['edit'] == "True") {
		$con->mark_read($_REQUEST['id']);
		$con->show_message_form();
	} else {
		$con->show_messages();
	}
	echo "</div>";
}

function contact_add_admin_pages() {
	require_once('../wp-content/plugins/wp-contact/class.contact.php');
	$con = new wp_contact();
	$num = $con->count_unread();
	add_management_page("Contact ($num)", "Contact ($num)", 7, 'contact_admin', 'contact_admin');
}
include ('wp-contact-widget.php');
add_action("admin_menu", "contact_add_admin_pages");





?>