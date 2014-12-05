<?php
/*
Plugin Name: Free Rabies Vaccine Offer Panel
Plugin URI: 
Description: Inserts Link to Rabies Vaccine Offer Page
Author: 
Version: 1
Author URI: 
*/
 
function widget_free_rabies() {
?>
<div id="free_rabies_widget">
	<a href="killer-savings/free-rabies-vaccine/">
		<span class="first"></span>

	</a>  
</div>

<?php
}
 
function free_rabies_init()
{
  register_sidebar_widget(__('Free Rabies Vaccine'), 'widget_free_rabies');
}
add_action("plugins_loaded", "free_rabies_init");

?>