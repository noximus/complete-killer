<?php
	ini_set('display_errors', 0);
	require_once('../../../../wp-config.php');
	$defaultImage = get_option('siteurl') . '/wp-content/plugins/wpcu3er/img/noImage.png';
	
	if($_GET['act'] == 'newST') {
		$i = $_GET['i'];
		$defaults = array(
			'duration' => 5,
			'color' => '0x000000',
			'target' => '_blank',
			'dtarget' => '_self',
			'type' => '3D',
			'columns' => 5,
			'rows' => 5,
			'type2D' => 'fade',
			'flipAngle' => 180,
			'flipOrder' => 90,
			'flipShader' => 'flat',
			'flipOrderFromCenter' => 'false',
			'flipDirection' => 'right',
			'flipColor' => '0xff0000',
			'flipBoxDepth' => 500,
			'flipDepth' => 300,
			'flipEasing' => 'Sine.easeInOut',
			'flipDuration' => 0.8,
			'flipDelay' => 0.15,
			'flipDelayRandomize' => 0,
			'image' => get_option('siteurl') . '/wp-content/plugins/wpcu3er/img/noImage.png'
		);
		if(is_numeric($_GET['copy'])) {
			$slide = $_POST['slide'][$_GET['copy']];
			$slide = cu3er__array_remove_empty($slide);
		}
		$query = mysql_query("SELECT * FROM `" . $wpdb->prefix . "cu3er__slideshows` WHERE `id`='".$_GET['id']."'");
		while($row = mysql_fetch_assoc($query)) {
			foreach($row as $key=>$value) {
				$row[$key] = stripslashes($value);
			}
			$slideshow = $row;
		}
		$query = mysql_query("SELECT * FROM `" . $wpdb->prefix . "cu3er__defaults` WHERE `slideshow_id`='".$_GET['id']."'");
		while($row = mysql_fetch_assoc($query)) {
			foreach($row as $key=>$value) {
				$row[$key] = stripslashes($value);
			}
			$default = cu3er__array_remove_empty($row);
		}
		?>
		
		<div class="block ui-widget ui-widget-content ui-helper-clearfix" style="display:none;">
			<div class="block-head">
				<div class="block-toggle"></div>
				<h4>#<?php echo ($i + 1); ?> Slide & Transition<?php echo (is_numeric($_GET['copy'])) ? ' - Duplicate of #' . ($_GET['copy'] +1) : ''; ?></h4> <span class="delete" style="float:right;color:red;"><a href="#">Delete</a></span> <span class="duplicate" style="float:right;"><a href="#" rel="<?php echo $i; ?>">Duplicate</a> </span>
			</div>
			<?php 
				$original = array('');
				if(is_numeric($_GET['copy'])) {
					$slide['flipDirection'] = implode(",", $slide['flipDirection']);
					$original = $slide;
					$slide = array_merge($defaults, $default, $slide);
				} else {
					$slide = array_merge($defaults, $default);
				}
				foreach($slide as $key=>$value) {
					$slide[$key] = stripslashes($value);
				}
			?>
			<div class="block-inner" style="display: none;">
				<div class="transHalf">
					<h4><span>Slide image</span></h4>
					<div class="inputs slide">
						<span class="imageHolder">
							<?php 
								if(cu3er__isImage($slideshow['images_folder'] . '/' . $slide['image'])) {
									$image = $slideshow['images_folder'] . '/' . $slide['image']; 
								} elseif(cu3er__isHttpPath($slide['image'])) {
									if(cu3er__isImage($slide['image'])) {
										$image = $slide['image'];
									} else {
										$image = $defaultImage;
									}
								} else {
									$image = $defaultImage;
								}
								$imgSize = getimagesize($image);
								$size = ($imgSize['width'] < $imgSize['height']) ? 'height="80"' : 'width="80"';
							?>
							<img src="<?php echo $image; ?>" <?php echo $size; ?> />
						</span>
						<div class="imageUrl">
							<input type="hidden" name="slide[<?php echo $i; ?>][position]" value="<?php echo $i; ?>" class="positionHidden">
							<label for="image<?php echo $i; ?>">Image URL:</label> <input type="text" class="imageField" name="slide[<?php echo $i; ?>][image]" id="image<?php echo $i; ?>" value="<?php echo $slide['image'] ?>" /> <input type="button" class="button upload_image_button" value="CHANGE" name="browse" />
						</div>
					</div>
				
					<h4><span>Slide settings</span></h4>
					<div class="inputs<?php echo ($original['duration'] == '') ? ' CU3ER-emptyValue' : ''; ?>">
						<label for="duration<?php echo $i; ?>">Duration:</label> <input type="text" name="slide[<?php echo $i; ?>][duration]" id="duration1" size="5" value="<?php echo $slide['duration'] ?>" /> <span>seconds</span>
					</div>					
					<div class="inputs<?php echo ($original['color'] == '') ? ' CU3ER-emptyValue' : ''; ?>">
						<label for="color<?php echo $i; ?>">Color:</label> <input type="text" name="slide[<?php echo $i; ?>][color]" id="color<?php echo $i; ?>" size="5" value="<?php echo str_replace('0x', '#', $slide['color']) ?>" class="color" />
					</div>
					<div class="inputs<?php echo ($original['caption'] == '') ? ' CU3ER-emptyValue' : ''; ?>">
						<label for="caption<?php echo $i; ?>">Caption:</label> <input type="text" name="slide[<?php echo $i; ?>][caption]" id="caption<?php echo $i; ?>" value="<?php echo $slide['caption'] ?>" />
					</div>
					<div class="inputs<?php echo ($original['link'] == '') ? ' CU3ER-emptyValue' : ''; ?>">
						<label for="link<?php echo $i; ?>">Link:</label> <input type="text" name="slide[<?php echo $i; ?>][link]" id="link<?php echo $i; ?>" value="<?php echo $slide['link'] ?>" />
					</div>
					<div class="inputs<?php echo ($original['target'] == '') ? ' CU3ER-emptyValue' : ''; ?>">
						<label for="target<?php echo $i; ?>">Target:</label>
						<select name="slide[<?php echo $i; ?>][target]" id="target<?php echo $i; ?>">
							<option value="_self"<?php echo ($slide['target'] == '_self') ? 'selected="selected"' : ''; ?>>_self</option>
							<option value="_blank"<?php echo ($slide['target'] == '_blank') ? 'selected="selected"' : ''; ?>>_blank</option>
							<option value="_parent"<?php echo ($slide['target'] == '_parent') ? 'selected="selected"' : ''; ?>>_parent</option>
							<option value="_top"<?php echo ($slide['target'] == '_top') ? 'selected="selected"' : ''; ?>>_top</option>
						</select>
					</div>
					<h4><span>Description box</span></h4>
					<div class="textarea<?php echo ($original['heading'] == '') ? ' CU3ER-emptyValue' : ''; ?>">
						<label for="heading<?php echo $i; ?>">Heading text:</label>
						<textarea name="slide[<?php echo $i; ?>][heading]" id="heading<?php echo $i; ?>" rows="4" cols="43"><?php echo stripslashes($slide['heading']); ?></textarea>
					</div>
					<div class="textarea<?php echo ($original['paragraph'] == '') ? ' CU3ER-emptyValue' : ''; ?>">
						<label for="paragraph<?php echo $i; ?>">Paragraph text:</label>
						<textarea name="slide[<?php echo $i; ?>][paragraph]" id="paragraph<?php echo $i; ?>" rows="4" cols="43"><?php echo stripslashes($slide['paragraph']); ?></textarea>
					</div>
					<div class="inputs<?php echo ($original['dlink'] == '') ? ' CU3ER-emptyValue' : ''; ?>">
						<label for="dlink<?php echo $i; ?>">Link:</label> <input type="text" name="slide[<?php echo $i; ?>][dlink]" id="dlink<?php echo $i; ?>" value="<?php echo $slide['dlink'] ?>" />
					</div>
					<div class="inputs<?php echo ($original['dtarget'] == '') ? ' CU3ER-emptyValue' : ''; ?>">
						<label for="dtarget<?php echo $i; ?>">Target:</label>
						<select name="slide[<?php echo $i; ?>][dtarget]" id="dtarget<?php echo $i; ?>">
							<option value="_self"<?php echo ($slide['dtarget'] == '_self') ? 'selected="selected"' : ''; ?>>_self</option>
							<option value="_blank"<?php echo ($slide['dtarget'] == '_blank') ? 'selected="selected"' : ''; ?>>_blank</option>
							<option value="_parent"<?php echo ($slide['dtarget'] == '_parent') ? 'selected="selected"' : ''; ?>>_parent</option>
							<option value="_top"<?php echo ($slide['dtarget'] == '_top') ? 'selected="selected"' : ''; ?>>_top</option>
						</select>
					</div>
				</div>
			
				<div class="transHalf noBorder">
					<h4><span>Transition settings</span></h4>
					<div class="inputs<?php echo ($original['type'] == '') ? ' CU3ER-emptyValue' : ''; ?>">
						<label>Transition type:</label>
						<input type="radio" name="slide[<?php echo $i; ?>][type]" value="3D"<?php echo ($slide['type'] == '3D') ? ' checked="checked"' : '' ?> /> 3D
						<input type="radio" name="slide[<?php echo $i; ?>][type]" value="2D"<?php echo ($slide['type'] == '2D') ? ' checked="checked"' : '' ?> /> 2D
					</div>
					<div class="inputs<?php echo ($original['columns'] == '') ? ' CU3ER-emptyValue' : ''; ?>">
						<label for="columns<?php echo $i; ?>">Columns:</label> <input type="text" name="slide[<?php echo $i; ?>][columns]" id="columns<?php echo $i; ?>" size="5" value="<?php echo $slide['columns'] ?>" />
					</div>
					<div class="inputs<?php echo ($original['rows'] == '') ? ' CU3ER-emptyValue' : ''; ?>">
						<label for="rows<?php echo $i; ?>">Rows:</label> <input type="text" name="slide[<?php echo $i; ?>][rows]" id="rows<?php echo $i; ?>" size="5" value="<?php echo $slide['rows'] ?>" />
					</div>
					<div class="inputs<?php echo ($original['type2d'] == '') ? ' CU3ER-emptyValue' : ''; ?>">
						<label for="type2d<?php echo $i; ?>">2D transition type:</label>
						<select name="slide[<?php echo $i; ?>][type2d]" id="type2d<?php echo $i; ?>">
							<option value="slide"<?php echo ($slide['type2d'] == 'slide') ? 'selected="selected"' : ''; ?>>Slide/Glide</option>
							<option value="fade"<?php echo ($slide['type2d'] == 'fade') ? 'selected="selected"' : ''; ?>>Fade</option>
						</select>
					</div>
					<div class="inputs<?php echo ($original['flipAngle'] == '') ? ' CU3ER-emptyValue' : ''; ?>">
						<label for="flipAngle<?php echo $i; ?>">Flip angle:</label>
						<select name="slide[<?php echo $i; ?>][flipAngle]" id="flipAngle<?php echo $i; ?>">
							<option value="180"<?php echo ($slide['flipAngle'] == 180) ? 'selected="selected"' : ''; ?>>180</option>
							<option value="90"<?php echo ($slide['flipAngle'] == 90) ? 'selected="selected"' : ''; ?>>90</option>
						</select>
					</div>
					<div class="inputs<?php echo ($original['flipOrder'] == '') ? ' CU3ER-emptyValue' : ''; ?>">
						<label for="flipOrder<?php echo $i; ?>">Flip order:</label>
						<select name="slide[<?php echo $i; ?>][flipOrder]" id="flipOrder<?php echo $i; ?>">
							<option value="0"<?php echo ($slide['flipOrder'] === 0) ? 'selected="selected"' : ''; ?>>From Left to Right</option>
							<option value="315"<?php echo ($slide['flipOrder'] == 315) ? 'selected="selected"' : ''; ?>>From Top-Left to Bottom-Right</option>
							<option value="270"<?php echo ($slide['flipOrder'] == 270) ? 'selected="selected"' : ''; ?>>From Top to Bottom</option>
							<option value="225"<?php echo ($slide['flipOrder'] == 225) ? 'selected="selected"' : ''; ?>>From Top-Right to Bottom-Left</option>
							<option value="180"<?php echo ($slide['flipOrder'] == 180) ? 'selected="selected"' : ''; ?>>From Right to Left</option>
							<option value="135"<?php echo ($slide['flipOrder'] == 135) ? 'selected="selected"' : ''; ?>>From Bottom-Right to Top-Left</option>
							<option value="90"<?php echo ($slide['flipOrder'] == 90) ? 'selected="selected"' : ''; ?>>From Bottom to Top</option>
							<option value="45"<?php echo ($slide['flipOrder'] == 45) ? 'selected="selected"' : ''; ?>>From Bottom-Left to Top-Right</option>
						</select>
					</div>
	
					<div class="inputs<?php echo ($original['flipOrderFromCenter'] == '') ? ' CU3ER-emptyValue' : ''; ?>">
						<label for="flipOrderFromCenter<?php echo $i; ?>">Flip from center:</label>
						<select name="slide[<?php echo $i; ?>][flipOrderFromCenter]" id="flipOrderFromCenter<?php echo $i; ?>">
							<option value="true"<?php echo ($slide['flipOrderFromCenter'] == 'true') ? 'selected="selected"' : ''; ?>>Yes</option>
							<option value="false"<?php echo ($slide['flipOrderFromCenter'] == 'false') ? 'selected="selected"' : ''; ?>>No</option>
						</select>
					</div>
					<div class="inputs<?php echo ($original['flipDirection'] == '') ? ' CU3ER-emptyValue' : ''; ?>">
						<?php $directions = (is_array($slide['flipDirection'])) ? $slide['flipDirection'] : explode(",", $slide['flipDirection']); ?>
						<label>Flip direction:</label> 
						<input type="checkbox" name="slide[<?php echo $i; ?>][flipDirection][0]" value="up"<?php echo (in_array('up', $directions)) ? ' checked="checked"' : ''; ?> /> UP
						<input type="checkbox" name="slide[<?php echo $i; ?>][flipDirection][1]" value="down"<?php echo (in_array('down', $directions)) ? ' checked="checked"' : ''; ?> /> DOWN
						<input type="checkbox" name="slide[<?php echo $i; ?>][flipDirection][2]" value="left"<?php echo (in_array('left', $directions)) ? ' checked="checked"' : ''; ?> /> LEFT
						<input type="checkbox" name="slide[<?php echo $i; ?>][flipDirection][3]" value="right"<?php echo (in_array('right', $directions)) ? ' checked="checked"' : ''; ?> /> RIGHT
					</div>
					
					<div class="inputs<?php echo ($original['flipColor'] == '') ? ' CU3ER-emptyValue' : ''; ?>">
						<label for="flipColor<?php echo $i; ?>">Flip color:</label> <input type="text" name="slide[<?php echo $i; ?>][flipColor]" id="flipColor<?php echo $i; ?>" size="5" value="<?php echo str_replace('0x', '#', $slide['flipColor']) ?>" class="color" />
					</div>
					<div class="inputs<?php echo ($original['flipShader'] == '') ? ' CU3ER-emptyValue' : ''; ?>">
						<label for="flipShader<?php echo $i; ?>">Use shading:</label>
						<select name="slide[<?php echo $i; ?>][flipShader]" id="flipShader<?php echo $i; ?>">
							<option value="flat"<?php echo ($slide['flipShader'] == 'flat') ? 'selected="selected"' : ''; ?>>Yes</option>
							<option value="none"<?php echo ($slide['flipShader'] == 'none') ? 'selected="selected"' : ''; ?>>No</option>
						</select>
					</div>					
					<div class="inputs<?php echo ($original['flipBoxDepth'] == '') ? ' CU3ER-emptyValue' : ''; ?>">
						<label for="flipBoxDepth<?php echo $i; ?>">Flip box thickness:</label> <input type="text" name="slide[<?php echo $i; ?>][flipBoxDepth]" id="flipBoxDepth<?php echo $i; ?>" size="5" value="<?php echo $slide['flipBoxDepth'] ?>" />
					</div>
					<div class="inputs<?php echo ($original['flipDepth'] == '') ? ' CU3ER-emptyValue' : ''; ?>">
						<label for="flipDepth<?php echo $i; ?>">Flip depth:</label> <input type="text" name="slide[<?php echo $i; ?>][flipDepth]" id="flipDepth<?php echo $i; ?>" size="5" value="<?php echo $slide['flipDepth'] ?>" />
					</div>
					<div class="inputs<?php echo ($original['flipEasing'] == '') ? ' CU3ER-emptyValue' : ''; ?>">
						<label for="flipEasing<?php echo $i; ?>">Flip easing:</label>
						<select name="slide[<?php echo $i; ?>][flipEasing]" id="flipEasing<?php echo $i; ?>">
							<option value="Sine.easeOut"<?php echo ($slide['flipEasing'] == 'Sine.easeOut') ? 'selected="selected"' : ''; ?>>Sine.easeOut</option>
							<option value="Sine.easeInOut"<?php echo ($slide['flipEasing'] == 'Sine.easeInOut') ? 'selected="selected"' : ''; ?>>Sine.easeInOut</option>
							<option value="Sine.easeIn"<?php echo ($slide['flipEasing'] == 'Sine.easeIn') ? 'selected="selected"' : ''; ?>>Sine.easeIn</option>
							<option value="Bounce.easeOut"<?php echo ($slide['flipEasing'] == 'Bounce.easeOut') ? 'selected="selected"' : ''; ?>>Bounce.easeOut</option>
							<option value="Bounce.easeInOut"<?php echo ($slide['flipEasing'] == 'Bounce.easeInOut') ? 'selected="selected"' : ''; ?>>Bounce.easeInOut</option>
							<option value="Bounce.easeIn"<?php echo ($slide['flipEasing'] == 'Bounce.easeIn') ? 'selected="selected"' : ''; ?>>Bounce.easeIn</option>
							<option value="Elastic.easeOut"<?php echo ($slide['flipEasing'] == 'Elastic.easeOut') ? 'selected="selected"' : ''; ?>>Elastic.easeOut</option>
							<option value="Elastic.easeInOut"<?php echo ($slide['flipEasing'] == 'Elastic.easeInOut') ? 'selected="selected"' : ''; ?>>Elastic.easeInOut</option>
							<option value="Elastic.easeIn"<?php echo ($slide['flipEasing'] == 'Elastic.easeIn') ? 'selected="selected"' : ''; ?>>Elastic.easeIn</option>
							<option value="Expo.easeOut"<?php echo ($slide['flipEasing'] == 'Expo.easeOut') ? 'selected="selected"' : ''; ?>>Expo.easeOut</option>
							<option value="Expo.easeInOut"<?php echo ($slide['flipEasing'] == 'Expo.easeInOut') ? 'selected="selected"' : ''; ?>>Expo.easeInOut</option>
							<option value="Expo.easeIn"<?php echo ($slide['flipEasing'] == 'Expo.easeIn') ? 'selected="selected"' : ''; ?>>Expo.easeIn</option>
						</select>
					</div>
					<div class="inputs<?php echo ($original['flipDuration'] == '') ? ' CU3ER-emptyValue' : ''; ?>">
						<label for="flipDuration<?php echo $i; ?>">Flip duration:</label> <input type="text" name="slide[<?php echo $i; ?>][flipDuration]" id="flipDuration<?php echo $i; ?>" size="5" value="<?php echo $slide['flipDuration'] ?>" />
					</div>
					<div class="inputs<?php echo ($original['flipDelay'] == '') ? ' CU3ER-emptyValue' : ''; ?>">
						<label for="flipDelay<?php echo $i; ?>">Flip delay:</label> <input type="text" name="slide[<?php echo $i; ?>][flipDelay]" id="flipDelay<?php echo $i; ?>" size="5" value="<?php echo $slide['flipDelay'] ?>" />
					</div>
					<div class="inputs<?php echo ($original['flipDelayRandomize'] == '') ? ' CU3ER-emptyValue' : ''; ?>">
						<label for="flipDelayRandomize<?php echo $i; ?>">Flip delay randomize:</label> <input type="text" name="slide[<?php echo $i; ?>][flipDelayRandomize]" id="flipDelayRandomize<?php echo $i; ?>" size="5" value="<?php echo $slide['flipDelayRandomize'] ?>" />
					</div>
					<div class="<?php echo ($original['x'] == '') ? 'CU3ER-emptyValue' : '' ?>">
						<input type="hidden" name="slide[<?php echo $i; ?>][align_pos]" value="<?php echo $slide['align_pos']; ?>" />
						<input type="hidden" name="slide[<?php echo $i; ?>][x]" value="<?php echo $slide['x']; ?>" />
						<input type="hidden" name="slide[<?php echo $i; ?>][y]" value="<?php echo $slide['y']; ?>" />
						<input type="hidden" name="slide[<?php echo $i; ?>][scaleX]" value="<?php echo $slide['scaleX']; ?>" />
						<input type="hidden" name="slide[<?php echo $i; ?>][scaleY]" value="<?php echo $slide['scaleY']; ?>" />
					</div>
				</div>
				<br class="clear"/> 
			</div>
		</div>
		<?php
	}
	elseif($_GET['act'] == 'add') {
		include_once('../tpl/ajax/add.php');
	}
	elseif($_GET['act'] == 'list') {
		global $wpdb;
		$js =  '<script type="text/javascript" src="' . get_option('siteurl') . '/wp-includes/js/jquery/jquery.js"></script>';
		if(is_numeric($_GET['id'])) {
			if($_POST['Submit'] == 'Save Changes') {
				if(is_numeric($_POST['slideshow_id'])) {
					mysql_query("DELETE FROM `" . $wpdb->prefix . "cu3er__slides` FROM `slideshow_id`='".$_POST['slideshow_id']."'");
					foreach($_POST['slide'] as $slide) {
						$slide['slideshow_id'] = $_POST['slideshow_id'];
						$slide['flipDirection'] = implode(",", $slide['flipDirection']);
						if(cu3er__sql_magic($wpdb->prefix . 'cu3er__slides', $slide)) {
							$message = $messages['success'];
						} else {
							$message = $messages['error'];
							echo mysql_error();
						}
					}
				} elseif($_GET['type'] == 'defaults') {
					$_POST['Defaults']['flipDirection'] = implode(",", $_POST['Defaults']['flipDirection']);
					$defaults = $_POST['Defaults'];
					if(cu3er__sql_magic($wpdb->prefix . 'cu3er__defaults', $defaults)) {
						$message = $messages['success'];
					} else {
						$message = $messages['error'];
						echo mysql_error();
					}
				} else {
					$_POST['background'] = ($_POST['background'] == 'transparent') ? 'transparent' : $_POST['background1'];
					unset($_POST['background1']);
					if(cu3er__sql_magic($wpdb->prefix . 'cu3er__slideshows', $_POST)) {
						$message .= $messages['success'];
					} else {
						$message .= $messages['error'];
						echo mysql_error();
					}
				}
			}
			$query = mysql_query("SELECT * FROM `" . $wpdb->prefix . "cu3er__slideshows` WHERE `id`='".$_GET['id']."'");
			while($row = mysql_fetch_assoc($query)) {
				foreach($row as $key=>$value) {
					$row[$key] = stripslashes($value);
				}
				$slideshow = $row;
			}
			$query = mysql_query("SELECT * FROM `" . $wpdb->prefix . "cu3er__defaults` WHERE `slideshow_id`='".$_GET['id']."'");
			while($row = mysql_fetch_assoc($query)) {
				foreach($row as $key=>$value) {
					$row[$key] = stripslashes($value);
				}
				$default = $row;
			}
			$query = mysql_query("SELECT * FROM `" . $wpdb->prefix . "cu3er__slides` WHERE `slideshow_id`='".$_GET['id']."' ORDER BY `position` ASC");
			while($row = mysql_fetch_assoc($query)) {
				foreach($row as $key=>$value) {
					$row[$key] = stripslashes($value);
				}
				$slides[] = $row;
			}
			include_once($path . 'tpl/ajax/edit.php');
		} else {
			$query = mysql_query("SELECT * FROM `" . $wpdb->prefix . "cu3er__slideshows`") or die(mysql_error());
			while($row = mysql_fetch_assoc($query)) {
				foreach($row as $key=>$value) {
					$row[$key] = stripslashes($value);
				}
				$slideshows[] = $row;
			}
			include_once('../tpl/ajax/manage.php');
		}
	}
	elseif($_GET['act'] == 'getTinyData') {
		global $wpdb;
		$query = mysql_query("SELECT * FROM `" . $wpdb->prefix . "cu3er__slideshows` WHERE `id`='".$_GET['id']."'");
		while($row = mysql_fetch_assoc($query)) {
			foreach($row as $key=>$value) {
				$row[$key] = stripslashes($value);
			}
			$slideshow = $row;
		}
		$slideshow['pluginURI'] = get_bloginfo('url') . '/wp-content/plugins/wpcu3er/';
		$slideshow['background'] = str_replace('0x', '#', $slideshow['background']);
		echo json_encode($slideshow);
	}
	elseif($_GET['act'] == 'slideshows') {
		global $wpdb;
		$query = mysql_query("SELECT `id`,`name` FROM `" . $wpdb->prefix . "cu3er__slideshows`");
		while($row = mysql_fetch_assoc($query)) {
			foreach($row as $key=>$value) {
				$row[$key] = stripslashes($value);
			}
			$slideshows[] = $row;
		}
		echo json_encode($slideshows);
	}
	elseif($_GET['act'] == 'preview') {
		$slideshow = is_numeric($_GET['id']) ? $_GET['id'] : null;
		$query = mysql_query("SELECT * FROM `" . $wpdb->prefix . "cu3er__settings` LIMIT 1") or die(mysql_error());
		while($row = mysql_fetch_assoc($query)) {
			foreach($row as $key=>$value) {
				$row[$key] = stripslashes($value);
			}
			$settings = str_replace("'", "\'", $row);
		}
		$query = mysql_query("SELECT * FROM `" . $wpdb->prefix . "cu3er__slideshows` WHERE `id`='".$slideshow."'") or die(mysql_error());
		while($row = mysql_fetch_assoc($query)) {
			foreach($row as $key=>$value) {
				$row[$key] = stripslashes($value);
			}
			$slideshowS = str_replace("'", "\'", $row);
		}
		$query = mysql_query("SELECT * FROM `" . $wpdb->prefix . "cu3er__defaults` WHERE `slideshow_id`='".$slideshow."'") or die(mysql_error());
		while($row = mysql_fetch_assoc($query)) {
			foreach($row as $key=>$value) {
				$row[$key] = stripslashes($value);
			}
			$default = str_replace("'", "\'", $row);
		}
		$query = mysql_query("SELECT * FROM `" . $wpdb->prefix . "cu3er__slides` WHERE `slideshow_id`='".$slideshow."' ORDER BY `position` ASC") or die(mysql_error());
		while($row = mysql_fetch_assoc($query)) {
			foreach($row as $key=>$value) {
				$row[$key] = stripslashes($value);
			}
			$slides[] = str_replace("'", "\'", $row);
		}
		if($settings['licence'] != '') {
			$arrXml['licence'] = "<![CDATA[" .stripslashes(trim($settings['licence'])). "]]>";
		}
		$arrXml['project_settings']['width'] = $slideshowS['width'];
		$arrXml['project_settings']['height'] = $slideshowS['height'];
		$arrXml['settings']['background']['color']['vle'] = str_replace('#', '0x', $slideshowS['background']);
		$arrXml['settings']['background']['color']['@attributes']['transparent'] = ($slideshowS['backgroundType'] == 'transparent') ? 'true' : 'false';
		$arrXml['settings']['background']['image']['url'] = "<![CDATA[" . $slideshowS['bg_image'] . "]]>";
		if($arrXml['settings']['background']['image']['url'] == '<![CDATA[]]>') {
			$arrXml['settings']['background']['image']['url'] = '';
		}
		$arrXml['settings']['background']['image']['@attributes']['use_image'] = $slideshowS['bg_use_image'];
		$arrXml['settings']['background']['image']['@attributes']['align_to'] = $slideshowS['bg_align_to'];
		$arrXml['settings']['background']['image']['@attributes']['align_pos'] = $slideshowS['bg_align_pos'];
		$arrXml['settings']['background']['image']['@attributes']['x'] = $slideshowS['bg_x'];
		$arrXml['settings']['background']['image']['@attributes']['y'] = $slideshowS['bg_y'];
		$arrXml['settings']['background']['image']['@attributes']['scaleX'] = $slideshowS['bg_scaleX'];
		$arrXml['settings']['background']['image']['@attributes']['scaleY'] = $slideshowS['bg_scaleY'];
		$arrXml['settings']['folder_images'] = "<![CDATA[" .$slideshowS['images_folder']. "]]>";
		if($arrXml['settings']['folder_images'] == '<![CDATA[]]>') {
			$arrXml['settings']['folder_images'] = '';
		}
		$arrXml['settings']['folder_fonts'] = "<![CDATA[" .$slideshowS['fonts_folder']. "]]>";
		if($arrXml['settings']['folder_fonts'] == '<![CDATA[]]>') {
			$arrXml['settings']['folder_fonts'] = '';
		}
		if($slideshowS['sdw_use_image'] == 'true' && $slideshowS['sdw_image'] != '') {
			$arrXml['settings']['shadow']['@attributes']['use_image'] = $slideshowS['sdw_use_image'];
			$arrXml['settings']['shadow']['@attributes']['show'] = $slideshowS['sdw_show'];
			$arrXml['settings']['shadow']['@attributes']['color'] = str_replace('#', '0x', $slideshowS['sdw_color']);
			$arrXml['settings']['shadow']['@attributes']['alpha'] = $slideshowS['sdw_alpha'];
			$arrXml['settings']['shadow']['@attributes']['blur'] = $slideshowS['sdw_blur'];
			$arrXml['settings']['shadow']['@attributes']['corner_TL'] = $slideshowS['sdw_corner_tl'];
			$arrXml['settings']['shadow']['@attributes']['corner_TR'] = $slideshowS['sdw_corner_tr'];
			$arrXml['settings']['shadow']['@attributes']['corner_BL'] = $slideshowS['sdw_corner_bl'];
			$arrXml['settings']['shadow']['@attributes']['corner_BR'] = $slideshowS['sdw_corner_br'];
			$arrXml['settings']['shadow']['url'] = $slideshowS['sdw_image'];
		}
		if($slideshowS['pr_image'] != '') {
			$arrXml['preloader']['image']['@attributes']['align_to'] = $slideshowS['pr_align_to'];
			$arrXml['preloader']['image']['@attributes']['align_pos'] = $slideshowS['pr_align_pos'];
			$arrXml['preloader']['image']['@attributes']['x'] = $slideshowS['pr_x'];
			$arrXml['preloader']['image']['@attributes']['y'] = $slideshowS['pr_y'];
			$arrXml['preloader']['image']['@attributes']['scaleX'] = $slideshowS['pr_scaleX'];
			$arrXml['preloader']['image']['@attributes']['scaleY'] = $slideshowS['pr_scaleY'];
			$arrXml['preloader']['image']['@attributes']['loader_direction'] = $slideshowS['pr_loader_direction'];
			$arrXml['preloader']['image']['@attributes']['alpha_loader'] = $slideshowS['pr_alpha_loader'];
			$arrXml['preloader']['image']['@attributes']['alpha_bg'] = $slideshowS['pr_alpha_bg'];
			$arrXml['preloader']['image']['@attributes']['tint_loader'] = $slideshowS['pr_tint_loader'];
			$arrXml['preloader']['image']['@attributes']['tint_bg '] = $slideshowS['pr_tint_bg '];
			$arrXml['preloader']['image']['url'] = $slideshowS['pr_image'];
		}
		$arrXml['defaults']['slide']['@attributes']['time'] = $default['duration'];
		$arrXml['defaults']['slide']['@attributes']['color'] = str_replace('#', '0x', $default['color']);
		$arrXml['defaults']['slide']['link']['vle'] = "<![CDATA[" .$default['link']. "]]>";
		if($arrXml['defaults']['slide']['link']['vle'] == '<![CDATA[]]>') {
			$arrXml['defaults']['slide']['link']['vle'] = '';
		}
		$arrXml['defaults']['slide']['link']['@attributes']['target'] = $default['target'];
		$arrXml['defaults']['slide']['description']['link']['vle'] = "<![CDATA[" .$default['dlink']. "]]>";
		if($arrXml['defaults']['slide']['description']['link']['vle'] == '<![CDATA[]]>') {
			$arrXml['defaults']['slide']['description']['link']['vle'] = '';
		}
		$arrXml['defaults']['slide']['description']['link']['@attributes']['target'] = $default['dtarget'];
		$arrXml['defaults']['slide']['image']['@attributes']['align_pos'] = $default['align_pos'];
		$arrXml['defaults']['slide']['image']['@attributes']['x'] = $default['x'];
		$arrXml['defaults']['slide']['image']['@attributes']['y'] = $default['y'];
		$arrXml['defaults']['slide']['image']['@attributes']['scaleX'] = $default['scaleX'];
		$arrXml['defaults']['slide']['image']['@attributes']['scaleY'] = $default['scaleY'];
		$arrXml['defaults']['transition']['@attributes']['type'] = $default['type'];
		$arrXml['defaults']['transition']['@attributes']['columns'] = $default['columns'];
		$arrXml['defaults']['transition']['@attributes']['rows'] = $default['rows'];
		$arrXml['defaults']['transition']['@attributes']['type2D'] = $default['type2d'];
		$arrXml['defaults']['transition']['@attributes']['flipAngle'] = $default['flipAngle'];
		$arrXml['defaults']['transition']['@attributes']['flipOrder'] = $default['flipOrder'];
		$arrXml['defaults']['transition']['@attributes']['flipShader'] = $default['flipShader'];
		$arrXml['defaults']['transition']['@attributes']['flipOrderFromCenter'] = $default['flipOrderFromCenter'];
		$arrXml['defaults']['transition']['@attributes']['flipDirection'] = $default['flipDirection'];
		$arrXml['defaults']['transition']['@attributes']['flipColor'] = str_replace('#', '0x', $default['flipColor']);
		$arrXml['defaults']['transition']['@attributes']['flipBoxDepth'] = $default['flipBoxDepth'];
		$arrXml['defaults']['transition']['@attributes']['flipDepth'] = $default['flipDepth'];
		$arrXml['defaults']['transition']['@attributes']['flipEasing'] = $default['flipEasing'];
		$arrXml['defaults']['transition']['@attributes']['flipDuration'] = $default['flipDuration'];
		$arrXml['defaults']['transition']['@attributes']['flipDelay'] = $default['flipDelay'];
		$arrXml['defaults']['transition']['@attributes']['flipDelayRandomize'] = $default['flipDelayRandomize'];
		$arrXml['slides']['@attributes']['align_pos'] = $default['salign_pos'];
		$arrXml['slides']['@attributes']['x'] = $default['sx'];
		$arrXml['slides']['@attributes']['y'] = $default['sy'];
		$arrXml['slides']['@attributes']['width'] = $default['swidth'];
		$arrXml['slides']['@attributes']['height'] = $default['sheight'];
		foreach($slides as $key=>$value) {
			$arrXml['slides'][$key]['slide']['url'] = "<![CDATA[" .((cu3er__isHttpPath($value['image'])) ? $value['image'] : cu3er__removeDomainName($value['image'])). "]]>";
			if($value['duration'] != $default['duration']) {
				$arrXml['slides'][$key]['slide']['@attributes']['time'] = $value['duration'];
			}
			$arrXml['slides'][$key]['slide']['image']['@attributes']['align_pos'] = $value['align_pos'];
			$arrXml['slides'][$key]['slide']['image']['@attributes']['x'] = $value['x'];
			$arrXml['slides'][$key]['slide']['image']['@attributes']['y'] = $value['y'];
			$arrXml['slides'][$key]['slide']['image']['@attributes']['scaleX'] = $value['scaleX'];
			$arrXml['slides'][$key]['slide']['image']['@attributes']['scaleY'] = $value['scaleY'];
			$arrXml['slides'][$key]['slide']['caption'] = "<![CDATA[" .$value['caption']. "]]>";
			if($arrXml['slides'][$key]['slide']['caption'] == '<![CDATA[]]>') {
				$arrXml['slides'][$key]['slide']['caption'] = '';
			}
			if($value['color'] != $default['color']) {
				$arrXml['slides'][$key]['slide']['@attributes']['color'] = str_replace('#', '0x', $value['color']);
			}
			if($value['link'] != $default['link']) {
				$arrXml['slides'][$key]['slide']['link']['vle'] = "<![CDATA[" .$value['link']. "]]>";
			}
			if($value['target'] != $default['target']) {
				$arrXml['slides'][$key]['slide']['link']['@attributes']['target'] = $value['target'];
			}
			if($arrXml['slides'][$key]['slide']['link']['vle'] == '<![CDATA[]]>') {
				$arrXml['slides'][$key]['slide']['link']['vle'] = '';
			}
			$arrXml['slides'][$key]['slide']['description']['heading'] = "<![CDATA[" .$value['heading']. "]]>";
			if($arrXml['slides'][$key]['slide']['description']['heading'] == '<![CDATA[]]>') {
				$arrXml['slides'][$key]['slide']['description']['heading'] = '';
			}
			$arrXml['slides'][$key]['slide']['description']['paragraph'] = "<![CDATA[" .$value['paragraph']. "]]>";
			if($arrXml['slides'][$key]['slide']['description']['paragraph'] == '<![CDATA[]]>') {
				$arrXml['slides'][$key]['slide']['description']['paragraph'] = '';
			}
			if($value['dlink'] != $default['dlink']) {
				$arrXml['slides'][$key]['slide']['description']['link']['vle'] = "<![CDATA[" .$value['dlink']. "]]>";
			}
			if($arrXml['slides'][$key]['slide']['description']['link']['vle'] == '<![CDATA[]]>') {
				$arrXml['slides'][$key]['slide']['description']['link']['vle'] = '';
			}
			if($value['dtarget'] != $default['dtarget']) {
				$arrXml['slides'][$key]['slide']['description']['link']['@attributes']['target'] = $value['dtarget'];
			}
			if($value['type'] != $default['type']) {
				$arrXml['slides'][$key]['transition']['@attributes']['type'] = $value['type'];
			}
			if($value['columns'] != $default['columns']) {
				$arrXml['slides'][$key]['transition']['@attributes']['columns'] = $value['columns'];
			}
			if($value['rows'] != $default['rows']) {
				$arrXml['slides'][$key]['transition']['@attributes']['rows'] = $value['rows'];
			}
			if($value['type2d'] != $default['type2d']) {
				$arrXml['slides'][$key]['transition']['@attributes']['type2D'] = $value['type2d'];
			}
			if($value['flipAngle'] != $default['flipAngle']) {
				$arrXml['slides'][$key]['transition']['@attributes']['flipAngle'] = $value['flipAngle'];
			}
			if($value['flipAngle'] != $default['flipAngle']) {
				$arrXml['slides'][$key]['transition']['@attributes']['flipOrder'] = $value['flipOrder'];
			}
			if($value['flipShader'] != $default['flipShader']) {
				$arrXml['slides'][$key]['transition']['@attributes']['flipShader'] = $value['flipShader'];
			}
			if($value['flipOrderFromCenter'] != $default['flipOrderFromCenter']) {
				$arrXml['slides'][$key]['transition']['@attributes']['flipOrderFromCenter'] = $value['flipOrderFromCenter'];
			}
			if($value['flipDirection'] != $default['flipDirection']) {
				$arrXml['slides'][$key]['transition']['@attributes']['flipDirection'] = $value['flipDirection'];
			}
			if($value['flipColor'] != $default['flipColor']) {
				$arrXml['slides'][$key]['transition']['@attributes']['flipColor'] = str_replace('#', '0x', $value['flipColor']);
			}
			if($value['flipBoxDepth'] != $default['flipBoxDepth']) {
				$arrXml['slides'][$key]['transition']['@attributes']['flipBoxDepth'] = $value['flipBoxDepth'];
			}
			if($value['flipDepth'] != $default['flipDepth']) {
				$arrXml['slides'][$key]['transition']['@attributes']['flipDepth'] = $value['flipDepth'];
			}
			if($value['flipEasing'] != $default['flipEasing']) {
				$arrXml['slides'][$key]['transition']['@attributes']['flipEasing'] = $value['flipEasing'];
			}
			if($value['flipDuration'] != $default['flipDuration']) {
				$arrXml['slides'][$key]['transition']['@attributes']['flipDuration'] = $value['flipDuration'];
			}
			if($value['flipDelay'] != $default['flipDelay']) {
				$arrXml['slides'][$key]['transition']['@attributes']['flipDelay'] = $value['flipDelay'];
			}
			if($value['flipDelayRandomize'] != $default['flipDelayRandomize']) {
				$arrXml['slides'][$key]['transition']['@attributes']['flipDelayRandomize'] = $value['flipDelayRandomize'];
			}
		}
		$array = cu3er__array_remove_empty($arrXml);
		$data['data'] = $array;
		if(cu3er__url_exists($settings['js_location'])) {
			$js_enquene = '<script type="text/javascript" src="'.cu3er__removeDomainName($settings['js_location']).'"></script>';
			$js_embed = "var CU3ER_object = new CU3ER('CU3ER".$slideshow."');";
		}
		echo '
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<script type="text/javascript" src="' . get_option('siteurl') . '/wp-includes/js/swfobject.js"></script>
'.$js_enquene.'
</head>
<body style="margin:0px; overflow:hidden;">';
		$params = ($slideshowS['backgroundType'] == 'transparent') ? "wmode : 'transparent'" : "bgcolor : '".str_replace('0x', '#', $slideshowS['background'])."'";
		$var = "<div id='CU3ER". $slideshow ."'>
		<script type='text/javascript'>
			var vars = { xml_location : \"".cu3er__removeDomainName($slideshowS['xml_location'])."\", xml_encoded: '".urlencode(cu3er__array2xml($data))."' };
			// add Flash embedding parameters, etc. wmode, bgcolor...
			var params = { ". $params ." };
			// Flash object attributes id and name
			var attributes = { id:'CU3ER".$slideshow."', name:'CU3ER".$slideshow."' };
			swfobject.embedSWF('". $settings['cu3er_location'] ."?" . time() . "', 'CU3ER".$slideshow."', ". $slideshowS['width'] .", ". $slideshowS['height'] .", '10.0.0', 'js/expressinstall.swf', vars, params, attributes );
			".$js_embed."
		</script>" . $slideshowS['content'] . '</div>';
		echo $var;
		echo '
		</body>
		</html>';
	}
	elseif($_GET['act'] == 'upload') {
		$uploadsDir = wp_upload_dir();
		$save_path = $uploadsDir['path'] .'/';
		$upload_name = array(
			0 => "async-upload",
			1 => "async-upload1",
			2 => "async-upload2");
		$uploadErrors = array(
			0 => "There is no error, the file uploaded with success",
			1 => "The uploaded file exceeds the upload_max_filesize directive in php.ini",
			2 => "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form",
			3 => "The uploaded file was only partially uploaded",
			4 => "No file was uploaded",
			6 => "Missing a temporary folder"
		);

		foreach($upload_name as $key=>$name) {
			if($_FILES[$name]["tmp_name"] != '') {
			$file_name = preg_replace('/[^.A-Z0-9_ !@#$%^&()+={}\[\]\',~`-]|\.+$/i', "", basename($_FILES[$name]['name']));
				@unlink($save_path.$file_name);
				if(!move_uploaded_file($_FILES[$name]["tmp_name"], $save_path.$file_name)) {
					echo("File could not be saved. " . $uploadErrors[$_FILES[$name]['error']]);
					exit(0);
				}
			}
		}

	}
	elseif($_GET['act'] == 'saveForPreview') {
		if(is_numeric($_POST['slideshow_id'])) {
			$_POST['default']['Defaults']['flipDirection'] = implode(",", $_POST['default']['Defaults']['flipDirection']);
			$defaults = $_POST['default']['Defaults'];
			$defaults['flipOrderFromCenter'] = ($defaults['flipOrderFromCenter'] != 'false') ? 'true' : 'false';
			$defaults['flipShader'] = ($defaults['flipShader'] != 'none') ? 'flat' : 'none';
			if(!cu3er__sql_magic($wpdb->prefix . 'cu3er__defaults', $defaults)) {
				echo mysql_error();
			}
			mysql_query("DELETE FROM `" . $wpdb->prefix . "cu3er__slides` WHERE `slideshow_id`='".$_POST['slideshow_id']."'");
			foreach($_POST['slide'] as $slide) {
				$slide['slideshow_id'] = $_POST['slideshow_id'];
				$slide['flipDirection'] = implode(",", $slide['flipDirection']);
				if(isset($slide['flipOrderFromCenter'])) {
					$slide['flipOrderFromCenter'] = ($slide['flipOrderFromCenter'] != 'false') ? 'true' : 'false';
				}
				if(isset($slide['flipShader'])) {
					$slide['flipShader'] = ($slide['flipShader'] != 'none') ? 'flat' : 'none';
				}
				unset($slide['id']);
				if(!cu3er__sql_magic($wpdb->prefix . 'cu3er__slides', $slide)) {
					echo mysql_error();
				}
			}
			$_POST['settings']['modified'] = date('Y-n-d H:i:s');
			if(!cu3er__sql_magic($wpdb->prefix . 'cu3er__slideshows', $_POST['settings'])) {
				echo mysql_error();
			}
		}
	}
	elseif($_GET['act'] == 'deleteProject' && is_numeric($_GET['id'])) {
		global $wpdb;
		$query = mysql_query("SELECT `id`, `post_title`, `post_type` FROM `". $wpdb->prefix ."posts` WHERE `post_content` LIKE '%[CU3ER slideshow=\'".$_GET['id']."\'%' AND `post_type` != 'revision' ORDER BY `post_type`");
		if(mysql_num_rows($query) > 0) {
			while($row = mysql_fetch_assoc($query)) {
				$title = ($row['post_title'] != '') ? $row['post_title'] : 'no title';
				$ids[$row['post_type']][] = '#' . $row['id'] . ' ('.$title.')';
			}
			foreach($ids as $key=>$value) {
				$res[] = $key . ": " . implode(", ", $value);
			}
			if(sizeof($res) > 1) {
				$response = '"type":"' . implode(", ", $res) .'"';
			} else {
				$response = '"type":"' . $res[0] .'"';
			}
			echo '{"error": "true", '. $response .'}';
		} else {
			mysql_query("DELETE FROM `". $wpdb->prefix ."cu3er__slideshows` WHERE `id`='".$_GET['id']."' LIMIT 1");
			mysql_query("DELETE FROM `". $wpdb->prefix ."cu3er__slides` WHERE `slideshow_id`='".$_GET['id']."'");
			mysql_query("DELETE FROM `". $wpdb->prefix ."cu3er__defaults` WHERE `slideshow_id`='".$_GET['id']."'");
			echo '{"error": "false"}';
		}
	}
	elseif($_GET['act'] == 'deleteProjects') {
		global $wpdb;
		mysql_query("TRUNCATE `" . $wpdb->prefix . "cu3er__settings`");
		mysql_query("TRUNCATE `" . $wpdb->prefix . "cu3er__defaults`");
		mysql_query("TRUNCATE `" . $wpdb->prefix . "cu3er__slideshows`");
		mysql_query("TRUNCATE `" . $wpdb->prefix . "cu3er__slides`");
	}

?>