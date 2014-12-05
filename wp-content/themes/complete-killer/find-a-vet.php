<?php
/**
 * Template Name: Vet Locator
 *
 */

get_header(); ?>


		
<div id="content_wrapper">
	<div id="content">
		<div id="interior_content">
			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
				<?php the_content(); ?>
				<?php endwhile; else: ?>
			<?php endif; ?>
		</div>

     <!-- Container element for all the Elements that are to be paginated -->

	<?php
		include_once("wp-content/plugins/vet_locator.php");
		$clinic_name = FALSE;
		$city = FALSE;
		$state = FALSE;
		$zipCode = FALSE;
		$locator = new Vet_Locator;
		if ($_POST["clinicName"] != "") {
			$clinic_name = trim($_POST["clinicName"]);
		}
		if ($_POST["city"] != "") {
			$city = trim($_POST["city"]);
		}
		if ($_POST["state"] != "") {
			$state = trim($_POST["state"]);
		}
		if ($_POST["zipCode"] != "") {
			$zipCode = trim($_POST["zipCode"]);
		}
		echo $locator->lookup_clinics($clinic_name, $city, $state, $zipCode);
		/*if ($_POST["state"] != "" || $_POST["state"] != "--") {
		echo $locator->lookup_clinics($clinic_name, $city, $state, $zipCode);
		}else {
			echo "hello world";
		}*/
	 ?> 
	 
	   <div id="Pagination"></div>
        <br style="clear:both;" />
  <A style="margin-left: 26px; text-decoration: underline; font-size: 9px;" HREF="javascript:history.go(-1)"> BACK</A>

       
	<div class="clear_both"></div>	
	</div>

	</div> <!-- end #content_wrapper -->
<?php get_footer(); ?>
	
        
        <script type="text/javascript"> 
        
            // This is a very simple demo that shows how a range of elements can
            // be paginated.
            // The elements that will be displayed are in a hidden DIV and are
            // cloned for display. The elements are static, there are no Ajax 
            // calls involved.
        
            /**
             * Callback function that displays the content.
             *
             * Gets called every time the user clicks on a pagination link.
             *
             * @param {int} page_index New Page index
             * @param {jQuery} jq the container with the pagination links as a jQuery object
             */
            function pageselectCallback(page_index, jq){
                var new_content = jQuery('#hiddenresult div.result:eq('+page_index+')').clone();
                $('#Searchresult').empty().append(new_content);
                
				return false;
            }
           
            /** 
             * Initialisation function for pagination
             */
            function initPagination() {
                // count entries inside the hidden content
                var num_entries = jQuery('#hiddenresult div.result').length;
/*                 console.log(num_entries); */
                // Create content inside pagination element
                $("#Pagination").pagination(num_entries, {
                    callback: pageselectCallback,
                    items_per_page:3 
					
                });
				
             }
            
            // When document is ready, initialize pagination
            $(window).load(function(){      
                initPagination();
            });
            
            
            
        </script> 