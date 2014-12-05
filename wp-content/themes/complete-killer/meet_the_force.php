<?php
/**
 * Template Name: Meet the Force
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
twitter: "If you hate fleas and ticks as much as I do, then you'll love this new commercial for #frontlineplus: {{url}}"
}

, email_template: "meet_template" 
}

</script>
	


<div id="content_wrapper">
	<div id="content">
		<div id="interior_content">
			<a  href="/wp-content/themes/complete-killer/video/meet-v2.f4v" style="display:block;width:865px;height:440px; margin-top:24px;" id="player"><img src="/wp-content/themes/complete-killer/images/borntokill.jpg" alt="Welcome to the home of the FRONTLINE Plus complete killing force, the fiercest team of liquidy green killers around. Be glad you’re not a flea or a tick, because by now you’d be a goner. Take a look around this site to learn more about the force. These little killers can be a big help for your pet—and for you. " height="440px;" width="865px" border="0" /></a>
<script type="text/javascript">// <![CDATA[


flowplayer("player", {src: '/wp-content/themes/complete-killer/flash/flowplayer/flowplayer-3.2.7.swf', wmode: 'transparent'}, {
	clip:{
		onStart: function(clip) {
		
			_gaq.push(['_trackEvent', 'Videos', 'Play', clip.url]);

		},

		// track pause event for this clip. time (in seconds) is also tracked
		onPause: function(clip) {
		
			_gaq.push(['_trackEvent', 'Videos', 'Pause', clip.url]);
		
		},

		// track stop event for this clip. time is also tracked
		onStop: function(clip) {
		
			_gaq.push(['_trackEvent', 'Videos', 'Stop', clip.url]);
		},

		// track finish event for this clip
		onFinish: function(clip) {

			_gaq.push(['_trackEvent', 'Videos', 'Finish', clip.url]);
		}
	
	}



});
 play: 
  	null, // or play: {opacity: 0}

$f('*').each(function()
{
	this.onFinish(function(){ 

		this.unload();
	});

});

// ]]> 
</script>

				<div class="addthis_toolbox meet">
					<div class="clear_both"></div>
					<div class="custom_images">
						<a class="twitter addthis_button_twitter"></a>
						<a class="facebook addthis_button_facebook"></a>
						<a class="email addthis_button_email"></a>
						<span>Share this: &nbsp; &nbsp; </span>
					</div>
				</div>
				<div class="clear_both"></div>
		</div>
	</div>
</div> <!-- end #content_wrapper -->
<?php get_footer(); ?>
