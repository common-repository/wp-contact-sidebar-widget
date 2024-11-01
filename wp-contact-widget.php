<?php
function widget_wp_contact_init() {
	if ( !function_exists('register_sidebar_widget') || !function_exists('register_widget_control') )
		return;	
	function widget_wp_contact() {
		echo '<li class="sidebox"><h2 class="sidebartitle">Contact</h2>';
		if (isset($_POST['contact_name']) && isset($_POST['contact_email']) && isset($_POST['contact_message'])) {
			require_once(ABSPATH.'wp-content/plugins/wp-contact/class.contact.php');
			$con = new wp_contact();
			$con->add_message();
			echo "Your message has been sent!";
		} else {
			echo "<form name=\"wp_contact_form\" method=\"post\" target=\"\">
			Name:<br />
			<input name=\"contact_name\" type=\"text\" value=\"$_REQUEST[contact_name]\" size=\"23\" />
			<br />
			Email:<br />
			<input name=\"contact_email\" type=\"text\" value=\"$_REQUEST[contact_email]\" size=\"23\" />
			<br />
			Message:
			<textarea name=\"contact_message\" cols=\"20\" rows=\"3\">$_REQUEST[contact_message]</textarea>
			<br />
			&nbsp;&nbsp;<input type=\"submit\" name=\"submit\" value=\" &nbsp;Send Message!&nbsp;\">
			</form>
			";
		}
			//if these links are removed the plugin will not work!
			echo ' <div align="right"><a href="http://www.linksback.org" title="Wordpress Plugins">Contact</a> by ';
			echo ' <a href="http://www.professional-landscape-design.info" title="Professional Landscape Design">PLD</a></div>';
			
		echo "</li>";
	}	
register_sidebar_widget('Contact', 'widget_wp_contact');
}

add_action('plugins_loaded', 'widget_wp_contact_init');
?>