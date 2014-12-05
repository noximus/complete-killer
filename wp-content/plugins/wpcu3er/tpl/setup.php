<?php
	# cannot call this file directly
	if ( strpos( basename( $_SERVER['PHP_SELF']) , __FILE__ ) !== false ) exit;
	# overview page
	include_once('header.php');
?>
<div class="wrap wrap-CU3ER">
	<div class="icon32" id="icon-options-general"><br></div>
	<h2>wpCU3ER Setup</h2>
	<?php include('warnings.php'); ?>
	<a name="uploadCU3ER"></a>
	<form method="post" action="admin.php?page=CU3ERSetup" enctype="multipart/form-data" class="CU3ER-">
		<input type="hidden" name="settings[id]" value="<?php echo $settings['id']; ?>" />
		<h3 id="swf-upload">CU3ER SWF & JavaScript File Upload</h3>
		<span><?php if (!empty($settings['cu3er_location']))  {?>Current CU3ER.swf location: <code><?php  echo $settings['cu3er_location'] ?></code><?php
        } else {
          echo "Missing CU3ER.swf";
        } ?></span>
		<div class="inputs">
		    <label for="cu3er_location">CU3ER.swf file:</label> <input type="file" name="cu3er_location" id="cu3er_location" accept="application/x-shockwave-flash" />
		</div>
		<?php if (!empty($settings['js_location'])) { ?><span>Current CU3ER.js location: <code><?php echo $settings['js_location'] ?></code></span><?php } ?>
		<div class="inputs">
		<label for="js_location">CU3ER.js file:</label> <input type="file" name="js_location" id="js_location" accept="application/javascript" /><span class="description">(optional) </span>
		</div>
		<h3>Licence</h3>
		<div class="inputs">
			<label for="licence">CU3ER Licence:</label> <input type="text" name="settings[licence]" id="licence" value="<?php echo $settings['licence'] ?>" size="36" /> <span class="description">NOTE: CU3ER brand removing will work only if correct license is used! <a href="http://docs.getcu3er.com/wpcu3er/setup#licence" target="_blank">Learn more.</a></span>
		</div>
		<input type="submit" class="button-primary setup-button" value="Save Changes" name="Submit">
	</form>
	<br class="clear"/>
</div>
