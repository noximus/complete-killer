<?php
/*
Plugin Name: Die Fleas, Die!
Plugin URI: 
Description: Link to learn how to apply FRONTLINE Plus
Author: 
Version: 1
Author URI: 
*/
 
function widget_dieFleasDie() {
?>
<div id="die_fleas_die">
<a href="killing-101/how-to-apply/">
	  <h2 class="widgettitle">DIE FLEAS, DIE!</h2>
	  
	  	<img src="<?php bloginfo('template_directory'); ?>/images/die_fleas_die.jpg" alt="Learn how to apply FRONTLINE Plus" />
	  </a>
</div>

<?php
}
 
function dieFleasDie_init()
{
  register_sidebar_widget(__('Die Fleas, Die!'), 'widget_dieFleasDie');
}
add_action("plugins_loaded", "dieFleasDie_init");

?>