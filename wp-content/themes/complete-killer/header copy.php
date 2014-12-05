<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<title><?php

	global $page, $paged;

	wp_title( '|', true, 'right' );

	bloginfo( 'name' );

	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " | $site_description";

	if ( $paged >= 2 || $page >= 2 )
		echo ' | ' . sprintf( __( 'Page %s', 'twentyten' ), max( $paged, $page ) );

	?></title>
	<meta property="og:title" content="Save big on FRONTLINE&copy; Plus"/>
    <meta property="og:url" content="http://www.completekiller.com/"/>
    <meta property="og:image" content="<?php bloginfo('template_url'); ?>/images/default.jpg"/>
    <meta property="og:site_name" content="FRONTLINE Plus Complete Killer"/>
<!--     <meta property="fb:admins" content="USER_ID"/> -->
    <meta property="og:description"
          content="I just got a valuable coupon for FRONTLINE Plus flea and tick killer and want you to save, too. Get your coupon here: http://www.completekiller.com/coupon"/>

	<meta charset="<?php bloginfo( 'charset' ); ?>" />

<!-- facebook meta tags -->
		
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />

<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

<!-- pulls from webfonts.fonts.com account -->
<script type="text/javascript" src="http://fast.fonts.com/jsapi/d01dad93-df49-434b-8b67-031bc69b8959.js"></script>
<script src="<?php bloginfo('template_url'); ?>/js/jquery-1.5.1.js" type="text/javascript"></script>
<script src="<?php bloginfo('template_url'); ?>/js/hover_intent.js" type="text/javascript"></script>
<script src="<?php bloginfo('template_url'); ?>/js/swfobject.js" type="text/javascript"></script>
<script src="<?php bloginfo('template_url'); ?>/js/jquery.browser.min.js" type="text/javascript"></script>

<script src="http://www.completekiller.com/assets/flash/fleagame/js/facebook_graph.js"  type="text/javascript"></script>

<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/jquery.pagination.js"></script> 

<!-- 
	include flowplayer JavaScript file that does  
	Flash embedding and provides the Flowplayer API.
-->

<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/flash/flowplayer/flowplayer-3.2.6.min.js"></script>
	

<!--[if lt IE 7]>
        <link rel="stylesheet" type="text/css" href="<?php bloginfo('template_url'); ?>/css/ie6.css" />
<![endif]-->

<!--[if lt IE 8]>
        <link rel="stylesheet" type="text/css" href="<?php bloginfo('template_url'); ?>/css/ie7.css">
<![endif]-->


<!--[if lt IE 8]>

<script type="text/javascript" src="http://www.completekiller.com/wp-content/themes/complete-killer/pngfix/iepngfix_tilebg.js"></script>

<style type="text/css">
img, div { behavior: url(http://www.completekiller.com/wp-content/themes/complete-killer/pngfix/iepngfix.htc) }
</style>

<!--end PNG fix -->

<![endif]-->

<!-- Google Analytics -->
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try{ 
var pageTracker = _gat._getTracker("UA-8316264-3");
pageTracker._trackPageview();
} catch(err) {} 
</script>
<!-- Google Analytics -->



<script type="text/javascript"> 

// creates the "mega" drop down menus 

$(document).ready(function()
{
    function showPopup( pop )
    {   
        $(pop).css('zIndex', 10000 );
        $('.popup').hide();
        $(pop).fadeIn('fast');
        $(pop).mouseleave(function(){ $(pop).hide(); });
    }
    
    $('.page-item-2 a').mouseenter(function() { showPopup( '#meet_the_force_drop'); });
    $('.page-item-5 a').mouseenter(function() { showPopup( '#killing_drop'); });
    $('.page-item-7 a').mouseenter(function() { showPopup( '#killer_savings_drop' ); });
    $('.page-item-11 a').mouseenter(function() { showPopup( '#time_killers_drop'); });
});
</script>

<script type="text/javascript"> 

// creates "Questions Answered" FAQ animations

$(document).ready(function()
	{	$('.page-id-35 ul .answer').slideToggle();
    	$('.page-id-35 ul .question').click(function(){
    		$(this).toggleClass('open')
    		$(this).parent().find('.answer').slideToggle();
    	});
    	
    });
</script>



<?php

if ( is_page('home') || $post->post_parent == '16' ) {    

// the page is "Home", or the parent of the page is "Home"
	
} elseif ( is_page('flea-killah') || $post->post_parent == '56' ) {	
	echo ('<script type="text/javascript">
			var flashvars = {};
			var params = {wmode:"transparent"};
			var attributes = {};
			attributes.id = "flea_killah_flash";
			swfobject.embedSWF("http://www.completekiller.com/wp-content/themes/complete-killer/flash/fleakilla/fleakilla.swf", "flea_killah_flash", "943", "610", "9.0.0", "http://www.completekiller.com/wp-content/themes/complete-killer/flash/expressInstall.swf", flashvars, params, attributes);
</script>');
} 	else { 
}	

?>


<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>
	<div id="wrapper">
		<div id="header">
			<a href="<?php bloginfo('url'); ?>" id="home_button">FRONTLINE Plus</a>
			<a href="<?php bloginfo('url'); ?>" id="complete_killer_btn">Complete Killer</a>
		
<!-- 		switches right button between two different offers -->
			<?php
				$links = array(array('url' => '../wp-content/themes/complete-killer/killer-savings/', 'name'=>'free_dose'),
				               array('url' => '../wp-content/themes/complete-killer/killer-savings/killer-savings-b/', 'name' => 'free_dose_b'));
				$num = array_rand($links);
				$item = $links[$num];
			
				printf('<a href="%s" id="%s">%s</a>', $item['url'], $item['name'], $item['name']);
			?>
		
		</div> <!-- closes div#header -->
	
		<div id="nav_wrapper">
			<ul id="nav">
				<?php wp_list_pages('title_li=&include=2,5,7,11'); ?>
			</ul>
		</div> <!-- closes div#nav_wrapper -->
		
		<?php include('mega_drop_downs.php'); ?>