<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<title><?php wp_title(''); ?></title>	
	
	<!-- begin facebook meta tags -->

	<?php

	global $post;     // if outside the loop

	if ( is_page('killer-savings')) {
    // This is facebook meta for Killer Savings share
		include('killer_meta.php');
	} elseif ( is_page('meet-the-force')) {
	// This is facebook meta for Meet The Force share
		include('meet_meta.php');
	} elseif ( is_page('how-to-apply')) {
	// This is facebook meta for Meet The Force share
		include('how_meta.php');
	} elseif ( is_page('our-guarantee')) {
	// This is facebook meta for Meet The Force share
		include('guarantee_meta.php');
	}
	
	else {
		include('default-meta.php');

	}
	?>
	<!-- end facebook meta tags -->


<style type="text/css">*{outline:none;}</style>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

<!-- pulls from webfonts.fonts.com account -->
<script type="text/javascript" src="http://fast.fonts.com/jsapi/d01dad93-df49-434b-8b67-031bc69b8959.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/jquery-1.5.1.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/hover_intent.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/jquery.browser.min.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/jquery.pagination.js"></script> 
<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/swfobject.js" type="text/javascript"></script>

<!-- include flowplayer JavaScript file that does Flash embedding and provides the Flowplayer API. //-->
<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/flash/flowplayer/flowplayer-3.2.6.min.js"></script>

<!--[if lt IE 7]>
<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_url'); ?>/css/ie6.css" />
<![endif]-->
<!--[if lt IE 8]>
<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_url'); ?>/css/ie7.css">
<![endif]-->
<!--[if lt IE 8]>
<style type="text/css">
img, div { behavior: http://www.completekiller.com/wp-content/themes/complete-killer/pngfix/iepngfix.htc) }
</style>
<![endif]-->
<!--[if lte IE 8]>
<style type="text/css">
	.page-id-33 .bold{font-family: 'Helvetica W01 Cn';}
</style>

<![endif]-->

<script type="text/javascript" src="http://www.completekiller.com/wp-content/themes/complete-killer/pngfix/iepngfix_tilebg.js"></script>
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
    $('.page-item-460 a').mouseenter(function() { showPopup( '#guarantee_drop'); });

    
    $('#free_dose').click(function(){
    	_gaq.push(['_trackEvent', 'Free Dose Button', 'Version A', '<?php wp_title(); ?>']);
    });
    
    $('#free_dose_b').click(function(){
    	_gaq.push(['_trackEvent', 'Free Dose Button', 'Version B', '<?php wp_title(); ?>']);
    });
});
</script>

<script type="text/javascript"> 

// creates "Questions Answered" FAQ animations
$(document).ready(function()
	{	
    	$('.page-id-35 ul .question').click(function(){
    		$(this).toggleClass('open')
    		$(this).parent().find('.answer').slideToggle();
    	});
    	
    });
</script>

<?php wp_head(); ?>

	<script type="text/javascript">
	
	  var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', 'UA-8316264-3']);
	  _gaq.push(['_trackPageview']);
	
	  (function() {
	    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();
	
	</script>

</head>

<body <?php body_class(); ?>>

<!--
Start of DoubleClick Floodlight Tag: Please do not remove
Activity name of this tag: CompleteKiller.com
URL of the webpage where the tag is expected to be placed: http://www.completekiller.com/
This tag must be placed between the <body> and </body> tags, as close as possible to the opening tag.
Creation Date: 04/14/2011
-->
<script type="text/javascript">
var axel = Math.random() + "";
var a = axel * 10000000000000;
document.write('<iframe src="http://fls.doubleclick.net/activityi;src=1132361;type=compl690;cat=compl917;ord=1;num=' + a + '?" width="1" height="1" frameborder="0" style="display:none"></iframe>');
</script>
<noscript>
<iframe src="http://fls.doubleclick.net/activityi;src=1132361;type=compl690;cat=compl917;ord=1;num=1?" width="1" height="1" frameborder="0" style="display:none"></iframe>
</noscript>
<!-- End of DoubleClick Floodlight Tag: Please do not remove -->	

<div id="fb-root">
<script type="text/javascript" src="http://connect.facebook.net/en_US/all.js"></script> 
<script type="text/javascript">

function flashAnalytics(swfpage,redirect) 
{
	// Swap entertainment for ent, because its blocked for corporate
	swfpage = swfpage.replace("entertainment","ent");
	var tracking = swfpage.replace("entertainment","ent");
	var label = "Flash Event";

	// Determine the GA label based on event path
	if(tracking.indexOf("fleas_and_desist") >= 0) { label = "Fleas And Desist"; }
	else if(tracking.indexOf("flea_killah") >= 0) { label = "Flea Killah"; }
	else if(tracking.indexOf("carousel") >= 0) { label = "Carousel"; }
	else if(tracking.indexOf("banner") >= 0) { label = "Banner"; }
	else if(tracking.indexOf("play_tv_spot") >= 0) { label = "TV Spot"; }
	else if(tracking.indexOf("play_education") >= 0) { label = "Education Video"; }
	else if(tracking.indexOf("link") >= 0) { label = "Frontline Link"; redirect = ""; }
	else if(tracking.toLowerCase().indexOf("6get2_download") >= 0) { label = "Coupon AB"; }

	// Check for presence of the GA tracker
	if(_gaq != undefined) { 
		// Set the event label
		//alert("label: " + label + ", page: " + swfpage);
		_gaq.push(['_trackEvent', label, swfpage, '<?php wp_title(); ?>']);
		
		// If a redirect is required process it after the event is tracked
		if( typeof(redirect) == "string" && redirect != ""){ 
			window.location.href = redirect; 
		}
	} else { 
		alert("analytics not loaded: " + tracking); 
	}
}



</script> 
</div>

<div id="wrapper">
<div id="header">
<a href="<?php bloginfo('url'); ?>" id="home_button">FRONTLINE Plus</a>
<a href="<?php bloginfo('url'); ?>" id="complete_killer_btn">Complete Killer</a>

<!-- switches right button between two different offers -->
<?php
$links = array(array('url' => '../wp-content/themes/complete-killer/killer-savings/', 'name'=>'free_dose'),
               array('url' => '../wp-content/themes/complete-killer/killer-savings/killer-savings-b/', 'name' => 'free_dose_b'));
$num = array_rand($links);
$item = $links[$num];

printf('<a href="%s" id="%s">%s</a>', $item['url'], $item['name'], $item['name']);
?>

</div>
<!-- closes div#header -->

	<div id="nav_wrapper">
			<ul id="nav">
				<?php wp_list_pages('title_li=&include=2,5,7,460,11'); ?>
			</ul>
		</div> <!-- closes div#nav_wrapper -->
		
		<?php include('mega_drop_downs.php'); ?>