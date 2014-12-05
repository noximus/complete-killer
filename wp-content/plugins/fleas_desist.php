<?php
/*
Plugin Name: Fleas & Desist Panel
Plugin URI: 
Description: Inserts Link to Fleas & Desist Game Page
Author: 
Version: 1
Author URI: 
*/
 
function widget_fleas_desist() {
?>
<div id="fleas_desist">
	<a href="time-killers/fleas-desist/">
		<span class="first"></span>
	</a>  

</div>

<?php
}
 
function fleas_desist_init()
{
  register_sidebar_widget(__('Fleas and Desist'), 'widget_fleas_desist');
}
add_action("plugins_loaded", "fleas_desist_init");

?>