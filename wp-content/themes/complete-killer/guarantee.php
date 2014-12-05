<?php
/**
 * Template Name: Guarantee
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
twitter: "I love this new commercial for the Satisfaction Plus Guarantee from #frontlineplus:  {{url}}"
}

, email_template: "guarantee_template" 
}

</script> 

		
<script type="text/javascript">
	$(document).ready(function(){
	
		// overlay popup
		$('.overlay').click(function(){
			$('.overlay').fadeOut("fast", "linear");
			$f().stop(src);

		});
		$('.close').click(function(){
			$('.overlay').fadeOut("fast", "linear");
						$f().stop(src);

		});

		$('.video').click(function(){
		var src = $(this).attr('href')
			$f().getClip(0).update({url:src});
			$f().play(src);
			$('.overlay').fadeToggle("fast", "linear");
					
			return false;
		});

			
	// flowplayer	
		$f("trailer", {src: '<?php bloginfo('template_url'); ?>/flash/flowplayer/flowplayer.commercial-3.2.7.swf', wmode: 'transparent'}, 
 		
 		{
 		key: '#$f30a657d31daa4d8f9f',
 		wmode: "transparent",
 		
		clip: {
		}
	});
});
</script>
<div class="overlay">
				<div class="cover">
					<div class="close">
					</div>
					 	<div id="trailer" style="width: 864px; height:486px; margin: 0 auto;" >
					 	</div>
							<div class="addthis_toolbox meet">
								<div class="clear_both">
								</div>
								<div class="custom_images">
									<a class="twitter addthis_button_twitter"></a>
									<a class="facebook addthis_button_facebook"></a>
									<a class="email addthis_button_email"></a>
									<span>Share this: &nbsp; &nbsp; </span>
									</div>
						</div><!-- close addthis -->
				</div> <!-- close cover -->
</div> <!-- end of overlay -->

<div id="content_wrapper">
	<div id="content">
				<div id="interior_content">
		
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