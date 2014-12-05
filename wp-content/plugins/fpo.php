<?php
/*
Plugin Name: Satisfaction Frontline Plus Guarantee
Plugin URI: 
Description: Inserts Link to Guarantee Page
Author: 
Version: 1
Author URI: 
*/
 
function widget_fpo() {
?>
<div id="fpo">
	<a href="our-guarantee"><img style="margin-left: 2px" src="<?php bloginfo('template_directory'); ?>/images/guar_module.jpg" alt="Learn about our Frontline Plus Guaranttee" />
</a>  

</div>

<?php
}
 
function fpo_init()
{
  register_sidebar_widget(__('Frontline Plus Guarantee'), 'widget_fpo');
}
add_action("plugins_loaded", "fpo_init");

?>