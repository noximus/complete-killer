<?php get_header(); ?>

<div id="content_wrapper">
<div id="content">
<div id="interior_content">
<?php 	if ( is_front_page()) { ?>

<style type="text/css"> #intro object { margin-top: 35px; }</style>

	<!--<div id ="intro" style="width:890px;height:439px;margin:0 auto; position: absolute; text-align: center;z-index: 100;">
		<div id="fleaIntro"  style="margin-top: 35px;"></div>
	</div>-->

<script type="text/javascript">
	/*var flashvars = {};
	var params = {allowFullScreen  : "false", allowScriptAccess: "always", wmode: "transparent"};
	var attributes = {};				
	swfobject.embedSWF("<? //bloginfo('template_url'); ?>/flash/fleintro.swf", "fleaIntro", "890", "309", "9.0.0","expressInstall.swf", flashvars, params, attributes);*/
</script>

<div id ="cu3ox" style="width:978px;height:439px;margin:0 auto; position: relative; left: -44px; margin: -10px 0 -30px 0; z-index: 1;">
	<div id="cu3ox1"></div>
</div>

<script type="text/javascript">
	/*var flashvars2 = {xmlpath   : "http://www.completekiller.com/wp-content/themes/complete-killer/flash/thinger/xml/component.xml"};
	var params = {allowFullScreen  : "false", allowScriptAccess: "always", wmode: "transparent"};
	var attributes = {id  : "sample", name: "sample"};
	swfobject.embedSWF("http://www.completekiller.com/wp-content/themes/complete-killer/flash/thinger/component.swf", "cu3ox1", "100%", "100%", "9.0.0","expressInstall.swf", flashvars2, params, attributes);
	*/
</script>

<!--<div class="hero_shadow"></div>-->

<script type="text/javascript">
      var flashvars = {};
      flashvars.cssSource = "http://completekiller.com/wp-content/themes/complete-killer/flash/piecemaker/piecemaker.css";
      flashvars.xmlSource = "http://completekiller.com/wp-content/themes/complete-killer/flash/piecemaker/piecemaker.xml";
		
      var params = {};
      params.play = "true";
      params.menu = "false";
      params.scale = "showall";
      params.wmode = "transparent";
      params.allowfullscreen = "true";
      params.allowscriptaccess = "always";
      params.allownetworking = "all";
	  
      swfobject.embedSWF('http://completekiller.com/wp-content/themes/complete-killer/flash/piecemaker/piecemaker.swf', 'cu3ox1', '100%', '100%', '10', null, flashvars, params, null);
    </script>

<script type="text/javascript">
	/*var timer1;
	var timer2;
	$(document).ready(function(){
		timer1 = setTimeout( showCube, 5000 );
		timer2 = setTimeout( killIntro, 5400 );
	});

	function showCube(){
		clearTimeout(timer1);
		$('#cu3ox').show();
	}
	
	function killIntro(){
		clearTimeout(timer2);
		$('#intro').remove();
	}*/
</script>
<? } ; ?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<?php the_content(); ?>

<?php
if( is_page('flea-killah') || $post->post_parent == '56' ) {
?>







<script> FB.init({appId:'167284006658518', status:false, cookie:true, xfbml:false}); </script>
<script src="http://www.completekiller.com/wp-content/themes/complete-killer/js/fleakillah_fb.js"  type="text/javascript"></script> 
<script>
var flashvars = {};
flashvars.standAlone = "<?php if(isset($_GET['share']) && !empty($_GET['share'])) echo 'true'; else echo 'false'; ?>";
flashvars.shareID = "<?php if(isset($_GET['share']) && !empty($_GET['share'])) echo $_GET['share']; ?>";
flashvars.pictureID = "";
flashvars.basepath = "<?php echo get_site_url(); ?>/";
flashvars.uploadpath = "<?php echo get_site_url(); ?>/index_ci.php/";
flashvars.savePath = "<?php echo get_site_url(); ?>/uploads/saved/";

var params = {};
params.wmode = "transparent";
params.menu = false;
params.salign = "tl";
params.scaleMode = "showAll";
params.allowscriptaccess = "always";

var attributes = {};
attributes.id = "flea_killah_flash";
swfobject.embedSWF("<?php echo get_site_url(); ?>/assets/flash/fleakilla/flealoader.swf?cb=<?php echo time();?>","flea_killah_flash","925","610","10","<?php echo get_site_url(); ?>/assets/scripts/swfobject/expressInstall.swf",flashvars,params,attributes);
</script>

<?php
} else if ( is_page('fleas-desist') || $post->post_parent == '15' ) { 
?>

<script src="http://www.completekiller.com/wp-content/themes/complete-killer/js/fleasdesist_fb.js"  type="text/javascript"></script> 
<script type="text/javascript">
var flashvars3 = {};
flashvars3.fbAppId = "208854552477975";
flashvars3.service_endpoint = "<?php echo get_site_url(); ?>/index_ci.php/game_remote";
flashvars3.assets_url = "<?php echo get_site_url(); ?>/assets/flash/fleagame/";
flashvars3.share_url = "<?php echo get_site_url(); ?>/time-killers/fleas_and_desist";
flashvars3.share_shortened_url = "http://bit.ly/9nAzPn";
flashvars3.share_icon_url = "<?php echo get_site_url(); ?>/assets/template/Fleas_Desist.png";
flashvars3.coupon_url = "http://www.completekiller.com/wp-content/themes/complete-killer/coupons/11FLMCRV%20Completekiller.com%20Coupon%20Viral%20Buy%206%20Get%202.pdf";
flashvars3.use_twitter_anywhere = true;
flashvars3.seed_score = "0";

var params = {}
params.bgcolor = "#E6F1F3";
params.scale = "showall";
params.salign = "tl";
params.wmode = "transparent";
params.base = "";
params.allowscriptaccess = "always";

var attributes = {};
attributes.name = "flea_desist_flash";
swfobject.embedSWF("<?php echo get_site_url(); ?>/assets/flash/fleagame/Stub.swf?cb=<?php echo time();?>", "flea_desist_flash", "943", "610", "10", "playerProductInstall.swf", flashvars3, params, attributes);
</script>

<script type="text/javascript">
fbInit('208854552477975','','flea_desist_flash');
</script>

<?php } 	?>
</div>

<?php 	
if ( is_front_page()) { 
	get_sidebar();
} else {

}
endwhile; else: ?>
<?php endif; ?>
<div class="clear_both"></div>
</div>
<div class="clear_both"></div>
</div>
<?php get_footer(); ?>