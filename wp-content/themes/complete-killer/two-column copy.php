<?php
/**
 * Template Name: Two Column
 *
 */

get_header(); ?>

 
<div id="content_wrapper">
	<div id="content">
		<div id="side_nav">
			<ul>
				<li><a class="inserted" href="../killing-101/">Fleas &amp; Ticks Exposed</a></li>
			 	<?php wp_list_pages('title_li=&child_of=5'); ?>
			</ul>
		</div>
		<div id="interior_content">
		
		<script type="text/javascript"> 
		$(document).ready(function()
				{	
					$('.video a').click(function(){
						$('div.overlay').fadeToggle("fast", "linear");
/* 						$('.player').fadeToggle("slow", "linear"); */
		
					});
					$('.overlay').click(function(){
						$('.overlay').fadeToggle("fast", "linear");
/* 						$('.player').fadeToggle("fast", "linear"); */
					});
		    	
		    });
		</script>
 
 
<!-- element that is overlayed, visuals are done with external CSS --> 
<div class="overlay">
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
twitter: "Sharpen your flea and tick killing skills with this helpful instructional video for using #frontlineplus: {{url}}"
}

, email_template: "how_template" 
}

</script> 
	<div class="cover">
		<div class="close"></div>

		 <div class="player">
					<a href="<?php bloginfo('template_url'); ?>/video/Frontline_Application.flv" style="display:block;width:865px;height:440px" id="player"><img src="<?php bloginfo('template_url'); ?>/images/how_to_apply.jpg" alt="video" />
</a><script>




flowplayer("player", "<?php bloginfo('template_url'); ?>/flash/flowplayer/flowplayer-3.2.7.swf", {
	clip:{
		onStart: function(clip) {
		
			_gaq.push(['_trackEvent', 'Videos', 'Play', clip.url]);

		},

		// track pause event for this clip. time (in seconds) is also tracked
		onPause: function(clip) {
		
			_gaq.push(['_trackEvent', 'Videos', 'Pause', clip.url - parseInt(this.getTime())]);
		
		},

		// track stop event for this clip. time is also tracked
		onStop: function(clip) {
		
			_gaq.push(['_trackEvent', 'Videos', 'Stop', clip.url - parseInt(this.getTime())]);
		},

		// track finish event for this clip
		onFinish: function(clip) {

			_gaq.push(['_trackEvent', 'Videos', 'Finish', clip.url]);
		}
	
	}



});


</script>
		</div>
		<div class="addthis_toolbox">
   			<div class="custom_images">
				<a class="twitter addthis_button_twitter"></a>
				<a class="facebook addthis_button_facebook"></a>
				<a class="email addthis_button_email"></a>
				<span>Share this:</span>
   			</div>
   		</div>
	</div> 
</div> 
		
		
		
		
		
			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
				<?php the_content(); ?>
				<?php endwhile; else: ?>
			<?php endif; ?>
		</div>
		<div class="clear_both"></div>
	</div>

		<div class="clear_both"></div>
</div> <!-- end #content_wrapper -->
<?php get_footer(); ?>

<div class="clear_both"></div>