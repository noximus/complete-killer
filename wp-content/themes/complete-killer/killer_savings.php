<?php
/**
 * Template Name: Killer Savings
 *
 */

get_header(); ?>
<!-- ADDTHIS BUTTON BEGIN -->
<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js"></script>

<script type="text/javascript">

var addthis_config = {
	 data_ga_property: 'UA-8316264-3',
     data_track_clickback: true,
     pubid: "ra-4e3b132568ffc3eb"
}

var addthis_share = 

{
templates: {
twitter: "I just got a big discount on #frontlineplus flea and tick killer. Get your coupon here: {{url}}"
}

, email_template: "killer_template" 
}

$('#b2g6download').live('click',function() {
  
  _gaq.push(["_trackPageview", "/killer-savings/coupon_B6G2"])
  
});


$('.coupon1down').live('click',function() {
	
  var axel = Math.random() + "";
  var a = axel * 10000000000000;
  document.getElementById("tracVarFrame").innerHTML = '<iframe src="http://fls.doubleclick.net/activityi;src=1132361;type=coupo935;cat=freed159;ord=1;num=' + a + '?" width="1" height="1" frameborder="0" style="display:none"></iframe>';

});
$('.addthis_button_email').live('click',function() {
  var axel = Math.random() + "";
  var a = axel * 10000000000000;
  document.getElementById("tracVarFrame").innerHTML = '<iframe src="http://fls.doubleclick.net/activityi;src=1132361;type=shari429;cat=email476;ord=1;num=' + a + '?" width="1" height="1" frameborder="0" style="display:none"></iframe>';

});
$('.addthis_button_facebook').live('click',function() {
  
  var axel = Math.random() + "";
  var a = axel * 10000000000000;
  document.getElementById("tracVarFrame").innerHTML = '<iframe src="http://fls.doubleclick.net/activityi;src=1132361;type=shari429;cat=faceb949;ord=1;num=' + a + '?" width="1" height="1" frameborder="0" style="display:none"></iframe>'
  
});
$('.addthis_button_twitter').live('click',function() {
  
  var axel = Math.random() + "";
  var a = axel * 10000000000000;
  document.getElementById("tracVarFrame").innerHTML = '<iframe src="http://fls.doubleclick.net/activityi;src=1132361;type=shari429;cat=twitt498;ord=1;num=' + a + '?" width="1" height="1" frameborder="0" style="display:none"></iframe>'
  
});
</script> 




<script type="text/javascript">

//html for popup goes here
var $player = $('<div class="cover" style="z-index:10000;"><div class="close"/><div class="player"><div id="social_coupon_offer"><a target="_blank" id="b2g6download" href="/wp-content/themes/complete-killer/coupons/11FLMCRV%20Completekiller.com%20Coupon%20Viral%20Buy%206%20Get%202.pdf">Download Coupon</a></div></div></div>');


function shareEventHandler(evt) {if (evt.type == 'addthis.menu.share')
/* {window.location = ("../wp-content/themes/complete-killer/coupons/Frontline_2010_FRT62.pdf")} */
{ 
			$('div.overlay').fadeToggle("fast", "linear");
			$('div.overlay').prepend($player);

			$('.overlay').click(function(){
				$(this).fadeToggle("fast", "linear");
			});
			$('.close').click(function(){
				window.location = ("../killer-savings/")
			});
			$('#social_coupon_offer a').click(function(){
				window.location = ("../killer-savings/")
			});


	    }
}addthis.addEventListener('addthis.menu.share', shareEventHandler);

</script>


<div id="content_wrapper">
	<div id="content">
		<div id="interior_content">
			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
				<?php the_content(); ?>
				<?php endwhile; else: ?>
			<?php endif; ?>
			<div class="clear_both"></div>
			<div class="find_a_vet">
				<?php include('find_vet_form.php'); ?>
			</div>
			<div id="details">
				<p><span style="display: block;" class="helv_bold">Coupon redeemable only at your vet's office</span></p>
<p style="width: 434px;">This coupon is provided in the Adobe&reg; PDF format. If you are unable to open or print the document, you may need to visit www.adobe.com to download free Acrobat Reader&reg; software. <a href="http://get.adobe.com/reader/" target="_blank">Acrobat Reader&reg; Download.</a></p>
<p style="width: 350px;">&reg;Adobe and Acrobat Reader are registered trademarks of Adobe Systems Incorporated.</p>
			</div>
	</div>



	
</div> <!-- end #content_wrapper -->
		<?php get_footer(); ?>
	