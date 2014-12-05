<?php
	/**
		Plugin Name: wpcu3er
		Plugin URI: http://getcu3er.com/
		Description: Plugin for embedding & managing CU3ER 3D slider in WordPress
		Author: MADEBYPLAY
		Version: 0.24
		Author URI: http://www.madebyplay.com/
	*/
	/*
		Copyright 2010  MADEBYPLAY  (email : support@getcu3er.com)
		
		This program is free software; you can redistribute it and/or modify
		it under the terms of the GNU General Public License, version 2, as 
		published by the Free Software Foundation.

		This program is distributed in the hope that it will be useful,
		but WITHOUT ANY WARRANTY; without even the implied warranty of
		MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
		GNU General Public License for more details.

		You should have received a copy of the GNU General Public License
		along with this program; if not, write to the Free Software
		Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
	*/
	ini_set('display_errors', 0);
	$cu3er_path = trailingslashit(dirname(__FILE__));
	$cu3er_messages = array(
		'noSWF' => '<div class="error">You are missing CU3ER.swf file. <a href="admin.php?page=CU3ERAddNew">Create</a> a project with using CU3ER zip project file or <a href="http:admin.php?page=CU3ERSetup">upload</a> <code>CU3ER.swf</code>.</div>',
		'noJS' => '<div class="error">Please upload <code>CU3ER.js</code>.',
		'setupSuccess' => '<div class="CU3ER-success updated below-h2" id="message"><p>Settings successfully saved.</p></div>',
		'success' => '<div class="CU3ER-success updated below-h2" id="message">CU3ER successfully saved.</div>',
		'error' => '<div class="error">Something went wrong, please try again.</div>',
		'version' => '<div class="error">wpCU3ER plugin requires WordPress 2.8 or newer. <a href="http://codex.wordpress.org/Upgrading_WordPress">Please update!</a></div>',
		'duplicated' => '<div id="message" class="CU3ER-success updated below-h2">CU3ER is successfully duplicated.</div>',
		'notLatest' => '<div class="error">You are not using the latest version of <code>CU3ER.swf</code>. <a href="admin.php?page=CU3ERAddNew&update=1">Click here</a> to update automatically from http://getcu3er.com to the latest version.</div>',
		'oldXML' => '<div class="error">You are using old version of XML! Please edit your newly uploaded slideshow and correct General Settings > SWF Size.</div>',
		'notXML' => '<div class="error">Missing or incomplete XML file, please use correct CU3ER zip project archive. <a class="button" href="admin.php?page=CU3ERAddNew">Try again</a> </div>',
		'updated' => '<div id="message" class="CU3ER-success updated below-h2"><code>CU3ER.swf</code> has been updated.</div>',
		'notUpdated' => '<div class="error">Something went wrong. <code>CU3ER.swf</code> has NOT been updated. Probably due lack of permissions.</div>',
		'missingXML' => '<div class="error">Could not locate XML file. Check if XML file exist, or if it is readable to script.</div>',
		'curlNotIntalled' => '<div class="error">PHP extension <code>curl</code> us not installed. You may experience a lack of functionality.</div>'
	);
	$cu3er_defaults = array(
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
		'flipShader' => 'plat',
		'flipOrderFromCenter' => 'false',
		'flipDirection' => 'right',
		'flipColor' => '0xff0000',
		'flipBoxDepth' => 500,
		'flipDepth' => 300,
		'flipEasing' => 'Sine.easeInOut',
		'flipDuration' => 0.8,
		'flipDelay' => 0.15,
		'flipDelayRandomize' => 0
	);
	
	global $wp_version;
	if (version_compare($wp_version, '2.8', '<')) {
		exit($cu3er_messages['version']);
	}

	
	function cu3er__admin_actions() {
		add_menu_page('CU3ER Admin Interface', 'CU3ER', 'edit_posts', 'CU3ER', 'cu3er__admin_overview', WP_PLUGIN_URL . '/wpcu3er/img/icon_cu3er.png');
		add_submenu_page('CU3ER', 'CU3ER Overview', 'Overview', 'edit_posts', 'CU3ER', 'cu3er__admin_overview');
		add_submenu_page('CU3ER', 'CU3ER Add SlideShow', 'Add New', 'edit_posts', 'CU3ERAddNew', 'cu3er__admin_add');
		add_submenu_page('CU3ER', 'CU3ER Manage SlideShows', 'Edit', 'edit_posts', 'CU3ERManageAll', 'cu3er__admin_manage');
		add_submenu_page('CU3ER', 'CU3ER Setup', 'Setup', 'edit_posts', 'CU3ERSetup', 'cu3er__admin_setup');
		add_submenu_page('CU3ER', 'CU3ER Help', 'Help', 'edit_posts', 'CU3ERHelp', 'cu3er__admin_help');
	}
	
	function cu3er__admin_help() {
		include_once($cu3er_path . 'tpl/help.php');
	}
	
	function cu3er__admin_overview() {
		global $wpdb;
		global $cu3er_messages;
		$message .= cu3er__admin_checkCu3er();
		$query = mysql_query("SELECT `id` FROM `" . $wpdb->prefix . "cu3er__slideshows`");
		while($row = mysql_fetch_assoc($query)) {
			$slideshows[] = $row;
		}
		$news = cu3er__our_fopen('http://205.186.133.41/templates/plugins/wordpress/notification.html');
		include_once($cu3er_path . 'tpl/overview.php');
	}
	
	function cu3er__admin_add() {
		global $wpdb;
		global $cu3er_messages;
		if($_GET['update'] == 1) {
			if(cu3er_download()) {
				$message .= cu3er__admin_checkCu3er(true);
			} else {
				$message .= cu3er__admin_checkCu3er(false);
			}
		} else {
			$message .= cu3er__admin_checkCu3er();
		}
		$uploadsDir = wp_upload_dir();
		if($_POST['Submit'] == 'Add CU3ER') {
			$rand = cu3er__getRand($uploadsDir['path'] .'/');
			$uplDir = $uploadsDir['path'] .'/'. $rand . '/';
			$save_path = $uploadsDir['url'] .'/'. $rand . '/';
			$uploadErrors = array(
				0 => "There is no error, the file uploaded with success",
				1 => "The uploaded file exceeds the upload_max_filesize directive in php.ini",
				2 => "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form",
				3 => "The uploaded file was only partially uploaded",
				4 => "No file was uploaded",
				6 => "Missing a temporary folder"
			);
			if(($_FILES['import1']['name'] != '') && ($_FILES['import1']["tmp_name"] != '' || $_FILES['import1']['error'] > 0)) {
				// if uploaded file //
				$file_name = preg_replace('/[^.A-Z0-9_ !@#$%^&()+={}\[\]\',~`-]|\.+$/i', "", basename($_FILES['import1']['name']));
				@unlink($uplDir.$file_name);
				if(!move_uploaded_file($_FILES['import1']["tmp_name"], $uplDir.$file_name)) {
					echo("<div class='error'>File could not be saved. " . $uploadErrors[$_FILES['import1']['error']] . '</div>');
					exit(0);
				} else {
					$testXmlFile  = $uplDir.$file_name;
				}
			} else {
				$testXmlFile = $_POST['import2'];
			}
			$zipFileType = false;
			$type = explode(".", basename($testXmlFile));
			if($type[1] == 'zip') {
				// if zip //
				include_once('php/pclzip.lib.php');
				$testXmlFile = $uploadsDir['path'] . '/' . $rand . '/' . basename($testXmlFile);
				$zip = new PclZip($testXmlFile);
				$dir = $uploadsDir['path'] . '/' . $rand . '/' . basename($testXmlFile, ".zip");
				$cu3er_pathDir = $uploadsDir['url'] . '/' . $rand . '/' . basename($testXmlFile, ".zip");
				if($zip->extract(PCLZIP_OPT_PATH, $dir) != 0) {
					// uploads dir //
					$cu3er_pathDir = $uploadsDir['url'] . '/' . $rand . '/' . basename($testXmlFile, ".zip");
					@mkdir($dir, 0777);
					if(file_exists($dir . '/CU3ER-config.xml')) {
						$xmlName[0] = 'CU3ER-config.xml';
					} else {
						$xmlName = glob($dir . '/*.xml');
					}
					if($xmlName[0] != '') {
						$testXmlFile = $dir .'/'. basename($xmlName[0]);
					}
				} else {
					die($zip->errorInfo(true));
				}
				$zipFileType = true;
			} else {
				// xml file //
				$cu3er_pathDir = $uploadsDir['url'] . '/' . $rand;
				$dir = $uploadsDir['path'] . '/' . $rand;
				$xmlName[0] = $testXmlFile;
			}
			if($testXmlFile != '') {
				$xmlStr = file_get_contents($testXmlFile);
				if(!file_exists($dir . '/' .basename($xmlName[0]))) {
					touch($dir . '/' . basename($xmlName[0]));
					$handle = fopen($dir . '/' . basename($xmlName[0]), 'w+');
					fwrite($handle, $xmlStr);
					fclose($handle);
				}
				include_once("php/xml2array.php");
				$xml_debugger = new XML2Array();
				if($xmlStr != '') {
					$xmlStr = preg_replace('/\<transition(.*?)\>/', '<transition empty="true"$1>', $xmlStr);
				}
				$arrXml = $xml_debugger->parse($xmlStr);
				if(!is_array($arrXml)) {
					$xmlStr = cu3er__our_fopen($testXmlFile);
					if($xmlStr == false) {
						cu3er__admin_manage($cu3er_messages['missingXML']);
					} else {
						$xmlStr = preg_replace('/\<transition(.*?)\>/', '<transition empty="true"$1>', $xmlStr);
						$arrXml = $xml_debugger->parse($xmlStr);
					}
				}
				if(!is_array($arrXml)) {
					cu3er__admin_manage($cu3er_messages['notXML']);
				} else {
					/*@chmod($testXmlFile, 0777);
					@chmod($cu3er_pathDir . '/CU3ER.swf', 0777);*/
					cu3er__chmodDir($dir, 0777, 0777);
					$arrXml = cu3er__array_remove_empty($arrXml['data']);
					// everything is ok //
					if($arrXml['project_settings']['width'] == '') {
						if($zipFileType) {
							$newStr = file_get_contents($dir . '/' . 'embed_example.html');
							preg_match_all('/swfobject\.embedSWF\((.*?)\)/', $newStr, $values);
							$dimensions = explode(",", $values[1][0]);
						} else {
							$dimensions[2] = $arrXml['slides']['attr']['width'];
							$dimensions[3] = $arrXml['slides']['attr']['height'];
						}
					}			
					$xml['Settings'] = array(
						'cu3er_location' => ($zipFileType) ? $cu3er_pathDir . '/CU3ER.swf' : '',
						'js_location' => ($zipFileType) ? $cu3er_pathDir . '/js/CU3ER.js' : '',
						'licence' => $licence
					);
				
					if(cu3er__isHttpPath($arrXml['settings']['folder_images']['value'])) {
						$urlFolderPath = true;
					}
					$xml['Slideshows'] = array(
						'name' => $_POST['name'],
						'width' => ($arrXml['project_settings']['width']['value'] != '') ? $arrXml['project_settings']['width']['value'] : trim($dimensions[2]),
						'height' => ($arrXml['project_settings']['height']['value'] != '') ? $arrXml['project_settings']['height']['value'] : trim($dimensions[3]),
						'background' => $arrXml['settings']['background']['color']['value'],
						'backgroundType' => ($arrXml['settings']['background']['color']['attr']['transparent'] == 'true') ? 'transparent' : 'color',
						'bg_image' => $arrXml['settings']['background']['image']['url']['value'],
						'bg_use_image' => $arrXml['settings']['background']['image']['attr']['use_image'],
						'bg_align_to' => $arrXml['settings']['background']['image']['attr']['align_to'],
						'bg_align_pos' => $arrXml['settings']['background']['image']['attr']['align_pos'],
						'bg_x' => $arrXml['settings']['background']['image']['attr']['x'],
						'bg_y' => $arrXml['settings']['background']['image']['attr']['y'],
						'bg_scaleX' => $arrXml['settings']['background']['image']['attr']['scaleX'],
						'bg_scaleY' => $arrXml['settings']['background']['image']['attr']['scaleY'],
						'sdw_show' => $arrXml['settings']['shadow']['attr']['show'],
						'sdw_use_image' => $arrXml['settings']['shadow']['attr']['use_image'],
						'sdw_image' => $arrXml['settings']['shadow']['url']['value'],
						'sdw_color' => $arrXml['settings']['shadow']['attr']['color'],
						'sdw_alpha' => $arrXml['settings']['shadow']['attr']['alpha'],
						'sdw_blur' => $arrXml['settings']['shadow']['attr']['blur'],
						'sdw_corner_tl' => $arrXml['settings']['shadow']['attr']['corner_TL'],
						'sdw_corner_tr' => $arrXml['settings']['shadow']['attr']['corner_TR'],
						'sdw_corner_bl' => $arrXml['settings']['shadow']['attr']['corner_BL'],
						'sdw_corner_br' => $arrXml['settings']['shadow']['attr']['corner_BR'],
						'pr_image' => $arrXml['preloader']['image']['url']['value'],
						'pr_align_to' => $arrXml['preloader']['image']['attr']['align_to'],
						'pr_align_pos' => $arrXml['preloader']['image']['attr']['align_pos'],
						'pr_x' => $arrXml['preloader']['image']['attr']['x'],
						'pr_y' => $arrXml['preloader']['image']['attr']['y'],
						'pr_scaleX' => $arrXml['preloader']['image']['attr']['scaleX'],
						'pr_scaleY' => $arrXml['preloader']['image']['attr']['scaleY'],
						'pr_loader_direction' => $arrXml['preloader']['image']['attr']['loader_direction'],
						'pr_alpha_loader' => $arrXml['preloader']['image']['attr']['alpha_loader'],
						'pr_alpha_bg' => $arrXml['preloader']['image']['attr']['alpha_bg'],
						'pr_tint_loader' => $arrXml['preloader']['image']['attr']['tint_loader'],
						'pr_tint_bg' => $arrXml['preloader']['image']['attr']['tint_bg'],
						'images_folder' => $cu3er_pathDir . '/images',
						'fonts_folder' => $cu3er_pathDir . '/fonts',
						'xml_location' => $cu3er_pathDir . '/' . basename($xmlName[0]),
						'modified' => date('Y-n-d H:i:s')
					);
					$xml['Defaults'] = array(
						'duration' => ($arrXml['defaults']['slide']['attr']['time'] > 0) ? $arrXml['defaults']['slide']['attr']['time'] : '',
						'color' => $arrXml['defaults']['slide']['attr']['color'],
						'link' => $arrXml['defaults']['slide']['link']['value'],
						'target' => $arrXml['defaults']['slide']['link']['attr']['target'],
						'dlink' => $arrXml['defaults']['slide']['description']['link']['value'],
						'dtarget' => $arrXml['defaults']['slide']['description']['link']['attr']['target'],
						'align_pos' => $arrXml['defaults']['slide']['image']['attr']['align_pos'],
						'x' => $arrXml['defaults']['slide']['image']['attr']['x'],
						'y' => $arrXml['defaults']['slide']['image']['attr']['y'],
						'scaleX' => $arrXml['defaults']['slide']['image']['attr']['scaleX'],
						'scaleY' => $arrXml['defaults']['slide']['image']['attr']['scaleY'],
						'type' => $arrXml['defaults']['transition']['attr']['type'],
						'columns' => ($arrXml['defaults']['transition']['attr']['columns'] > 0) ? $arrXml['defaults']['transition']['attr']['columns'] : '',
						'rows' => ($arrXml['defaults']['transition']['attr']['rows'] > 0) ? $arrXml['defaults']['transition']['attr']['rows'] : '',
						'type2d' => $arrXml['defaults']['transition']['attr']['type2d'],
						'flipAngle' => $arrXml['defaults']['transition']['attr']['flipAngle'],
						'flipOrder' => $arrXml['defaults']['transition']['attr']['flipOrder'],
						'flipShader' => $arrXml['defaults']['transition']['attr']['flipShader'],
						'flipOrderFromCenter' => $arrXml['defaults']['transition']['attr']['flipOrderFromCenter'],
						'flipDirection' => $arrXml['defaults']['transition']['attr']['flipDirection'],
						'flipColor' => $arrXml['defaults']['transition']['attr']['flipColor'],
						'flipBoxDepth' => $arrXml['defaults']['transition']['attr']['flipBoxDepth'],
						'flipDepth' => $arrXml['defaults']['transition']['attr']['flipDepth'],
						'flipEasing' => $arrXml['defaults']['transition']['attr']['flipEasing'],
						'flipDuration' => $arrXml['defaults']['transition']['attr']['flipDuration'],
						'flipDelay' => $arrXml['defaults']['transition']['attr']['flipDelay'],
						'flipDelayRandomize' => $arrXml['defaults']['transition']['attr']['flipDelayRandomize'],
						'salign_pos' => $arrXml['slides']['attr']['align_pos'],
						'sx' => $arrXml['slides']['attr']['x'],
						'sy' => $arrXml['slides']['attr']['y'],
						'swidth' => $arrXml['slides']['attr']['width'],
						'sheight' => $arrXml['slides']['attr']['height']
					);
					$i = 1;
					foreach($arrXml['slides']['slide'] as $key=>$value) {
						if(cu3er__isHttpPath($arrXml['slides']['slide'][$key]['url']['value'])) {
							$image = $arrXml['slides']['slide'][$key]['url']['value'];
						}
						elseif($urlFolderPath) {
							$image = $arrXml['settings']['folder_images']['value'] . $arrXml['slides']['slide'][$key]['url']['value'];
						} else {
							$image = ($zipFileType) ? basename($arrXml['slides']['slide'][$key]['url']['value']) : $arrXml['slides']['slide'][$key]['url']['value'];
						}
						$xml['Slides'][] = array(
							'image' => $image,
							'duration' => $arrXml['slides']['slide'][$key]['attr']['time'],
							'caption' => $arrXml['slides']['slide'][$key]['caption']['value'],
							'color' => $arrXml['slides']['slide'][$key]['attr']['color'],
							'link' => $arrXml['slides']['slide'][$key]['link']['value'],
							'target' => $arrXml['slides']['slide'][$key]['link']['attr']['target'],
							'align_pos' => $arrXml['slides']['slide'][$key]['image']['attr']['align_pos'],
							'x' => $arrXml['slides']['slide'][$key]['image']['attr']['x'],
							'y' => $arrXml['slides']['slide'][$key]['image']['attr']['y'],
							'scaleX' => $arrXml['slides']['slide'][$key]['image']['attr']['scaleX'],
							'scaleY' => $arrXml['slides']['slide'][$key]['image']['attr']['scaleY'],
							'heading' => (is_array($arrXml['slides']['slide'][$key]['description']['heading'])) ? $arrXml['slides']['slide'][$key]['description']['heading']['value'] : '',
							'paragraph' => (is_array($arrXml['slides']['slide'][$key]['description']['paragraph'])) ? $arrXml['slides']['slide'][$key]['description']['paragraph']['value'] : '',
							'dlink' => (is_array($arrXml['slides']['slide'][$key]['description']['link'])) ? $arrXml['slides']['slide'][$key]['description']['link']['value'] : '',
							'dtarget' => (is_array($arrXml['slides']['slide'][$key]['description']['link']['attr'])) ? $arrXml['slides']['slide'][$key]['description']['link']['attr']['target'] : '',
							'type' => $arrXml['slides']['transition'][$key]['attr']['type'],
							'columns' => $arrXml['slides']['transition'][$key]['attr']['columns'],
							'rows' => $arrXml['slides']['transition'][$key]['attr']['rows'],
							'type2d' => $arrXml['slides']['transition'][$key]['attr']['type2d'],
							'flipAngle' => $arrXml['slides']['transition'][$key]['attr']['flipAngle'],
							'flipOrder' => $arrXml['slides']['transition'][$key]['attr']['flipOrder'],
							'flipShader' => $arrXml['slides']['transition'][$key]['attr']['flipShader'],
							'flipOrderFromCenter' => $arrXml['slides']['transition'][$key]['attr']['flipOrderFromCenter'],
							'flipDirection' => $arrXml['slides']['transition'][$key]['attr']['flipDirection'],
							'flipColor' => $arrXml['slides']['transition'][$key]['attr']['flipColor'],
							'flipBoxDepth' => $arrXml['slides']['transition'][$key]['attr']['flipBoxDepth'],
							'flipDepth' => $arrXml['slides']['transition'][$key]['attr']['flipDepth'],
							'flipEasing' => $arrXml['slides']['transition'][$key]['attr']['flipEasing'],
							'flipDuration' => $arrXml['slides']['transition'][$key]['attr']['flipDuration'],
							'flipDelay' => $arrXml['slides']['transition'][$key]['attr']['flipDelay'],
							'flipDelayRandomize' => $arrXml['slides']['transition'][$key]['attr']['flipDelayRandomize'],
							'position' => $i
						);
						$i++;
					}
					$xml['Slideshows']['name'] = ($xml['Slideshows']['name'] != '') ? $xml['Slideshows']['name'] : 'CU3ER Slideshow '.date('d-M-Y H:i:s');
					if(cu3er__sql_magic($wpdb->prefix . 'cu3er__slideshows', $xml['Slideshows'])) {
						$id = mysql_insert_id();
						$xml['Defaults']['slideshow_id'] = $id;
						cu3er__sql_magic($wpdb->prefix . 'cu3er__defaults', $xml['Defaults']);
						foreach($xml['Slides'] as $slide) {
							$slide['slideshow_id'] = $id;
							cu3er__sql_magic($wpdb->prefix . 'cu3er__slides', $slide);
						}
						$query = mysql_query("SELECT * FROM `". $wpdb->prefix ."cu3er__settings` WHERE `id`=1");
						while($row = mysql_fetch_assoc($query)) {
							foreach($row as $key=>$value) {
								$row[$key] = stripslashes($value);
							}
							$settings = $row;
						}
						if($settings['cu3er_location'] != '') {
							if(cu3er__url_exists($settings['cu3er_location'])) {
								unset($xml['Settings']['cu3er_location']);
							}
						}
						if($settings['js_location'] != '') {
							if(cu3er__url_exists($settings['js_location'])) {
								unset($xml['Settings']['js_location']);
							}
						}
						if($settings['licence'] != '') {
							unset($xml['Settings']['licence']);
						}
						if($settings['id'] == 1) {
							$xml['Settings']['id'] = 1;
						}
						cu3er__sql_magic($wpdb->prefix . 'cu3er__settings', $xml['Settings']);
				
						if(!is_numeric($xml['Slideshows']['width']) || $xml['Slideshows']['width'] < 1) {
							cu3er__admin_manage($cu3er_messages['oldXML']);
						} else {
							cu3er__admin_manage();
						}
				
					} else {
						echo mysql_error();
					}
				}
			} else {
				cu3er__admin_manage($cu3er_messages['notXML']);
			}
		} else {
			include_once($cu3er_path . 'tpl/add.php');
		}
	}
	function cu3er__url_exists($url) {
		// Version 4.x supported
		if(CU3ER_isCurlInstalled()) {
		$handle   = @curl_init($url);
			@curl_setopt($handle, CURLOPT_FAILONERROR, true);  // this works
			@curl_setopt($handle, CURLOPT_HEADER, false);
			@curl_setopt($handle, CURLOPT_HTTPHEADER, Array("User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.15) Gecko/20080623 Firefox/2.0.0.15") ); // request as if Firefox
			@curl_setopt($handle, CURLOPT_NOBODY, true);
			@curl_setopt($handle, CURLOPT_RETURNTRANSFER, false);
			$connectable = @curl_exec($handle);
			@curl_close($handle);
		} else {
			$handle = @fopen($url, 'r');
			if($handle === false) {
				return false;
			} else {
				return true;
			}
		}
		return $connectable;
	}

	function cu3er__admin_checkCu3er($updated = null) {
		global $wpdb;
		global $cu3er_messages;
		$query = mysql_query("SELECT * FROM `" . $wpdb->prefix . "cu3er__settings` WHERE `id`=1");
		while($row = mysql_fetch_assoc($query)) {
			foreach($row as $key=>$value) {
				$row[$key] = stripslashes($value);
			}
			$settings = $row;
		}
		if($settings['cu3er_location'] != '') {
			$handle = cu3er__url_exists($settings['cu3er_location']);
			if($handle === false) {
				$message .= $cu3er_messages['noSWF'];
			} else {
				$locName = (cu3er__ffilesize('http://205.186.133.41/CU3ER.swf') === 0) ? 'getcu3er.com' : '205.186.133.41';
				if(cu3er__ffilesize($settings['cu3er_location']) != cu3er__ffilesize('http://'.$locName.'/CU3ER.swf')) {
					$message .= $cu3er_messages['notLatest'];
				}
			}
		} else {
			$message .= $cu3er_messages['noSWF'];
		}
		if(!CU3ER_isCurlInstalled()) {
			$message .= $cu3er_messages['curlNotIntalled'];
		}
		if($updated === true) {
			$message .= $cu3er_messages['updated'];
		}
		if($updated === false) {
			$message .= $cu3er_messages['notUpdated'];
		}
		return $message;
	}
	function cu3er__admin_manage($msg = '') {
		global $wpdb;
		global $cu3er_messages;
		global $cu3er_defaults;
		
		$query = mysql_query("SELECT `id` FROM `". $wpdb->prefix ."posts` ORDER BY `id` LIMIT 1");
		$row = mysql_fetch_assoc($query);
		$cu3er_post_id = $row['id'];
		
		$defaultImage = get_option('siteurl') . '/wp-content/plugins/wpcu3er/img/noImage.png';
		$message .= cu3er__admin_checkCu3er() . $msg;
		if($_GET['duplicate'] == 'true' && is_numeric($_GET['id'])) {
			$query = mysql_query("SELECT * FROM `" . $wpdb->prefix . "cu3er__slideshows` WHERE `id`='".$_GET['id']."'");
			while($row = mysql_fetch_assoc($query)) {
				foreach($row as $key=>$value) {
					$row[$key] = stripslashes($value);
				}
				$slideshow = $row;
			}
			$slideshow['name'] .= ' copy';
			unset($slideshow['id']);
			if(cu3er__sql_magic($wpdb->prefix . 'cu3er__slideshows', $slideshow)) {
				$id = mysql_insert_id();
				$query = mysql_query("SELECT * FROM `" . $wpdb->prefix . "cu3er__defaults` WHERE `slideshow_id`='".$_GET['id']."'");
				while($row = mysql_fetch_assoc($query)) {
					foreach($row as $key=>$value) {
						$row[$key] = stripslashes($value);
					}
					$default = $row;
				}
				$default['slideshow_id'] = $id;
				unset($default['id']);
				cu3er__sql_magic($wpdb->prefix . 'cu3er__defaults', $default);
				$query = mysql_query("SELECT * FROM `" . $wpdb->prefix . "cu3er__slides` WHERE `slideshow_id`='".$_GET['id']."' ORDER BY `position` ASC");
				while($row = mysql_fetch_assoc($query)) {
					foreach($row as $key=>$value) {
						$row[$key] = stripslashes($value);
					}
					$row['slideshow_id'] = $id;
					unset($row['id']);
					cu3er__sql_magic($wpdb->prefix . 'cu3er__slides', $row);
				}
			}
			$message .= $cu3er_messages['duplicated'];
			$query = mysql_query("SELECT * FROM `" . $wpdb->prefix . "cu3er__slideshows`") or die(mysql_error());
			while($row = mysql_fetch_assoc($query)) {
				foreach($row as $key=>$value) {
					$row[$key] = stripslashes($value);
				}
				$slideshows[] = $row;
			}
			include_once($cu3er_path . 'tpl/manage.php');
		}
		elseif(is_numeric($_GET['id']) && $_GET['type'] == 'xml') {
			if($_POST['submit'] == 'Upload') {
				if($_FILES['newXML']["tmp_name"] != '') {
					$continue = true;
					$query = mysql_query("SELECT `xml_location`, `width`, `height` FROM `" . $wpdb->prefix . "cu3er__slideshows` WHERE `id`='".$_GET['id']."'");
					while($row = mysql_fetch_assoc($query)) {
						$dir = $row['xml_location'];
						$w = $row['width'];
						$h = $row['height'];
					}
					$uploadsDir = wp_upload_dir();
					$pth = explode("/", $dir);
					$delFlag = true;
					$size = sizeof($pth);
					for($i=0; $i<$size; $i++) {
						if($pth[$i] == date('Y')) {
							unset($pth[$i], $pth[($i+1)]);
							$delFlag = false;
						} 
						if($delFlag == true) {
							unset($pth[$i]);
						}
					}
					unset($pth[($size-1)]);
					$cu3er_pathDir = $uploadsDir['url'] . '/' . implode("/", $pth);
					$dir = $uploadsDir['path'] . '/' . implode("/", $pth);
			
					$uploadErrors = array(
						0 => "There is no error, the file uploaded with success",
						1 => "The uploaded file exceeds the upload_max_filesize directive in php.ini",
						2 => "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form",
						3 => "The uploaded file was only partially uploaded",
						4 => "No file was uploaded",
						6 => "Missing a temporary folder"
					);

					if($_FILES['newXML']["tmp_name"] != '') {
				 		// if uploaded file //
				 		$file_name = preg_replace('/[^.A-Z0-9_ !@#$%^&()+={}\[\]\',~`-]|\.+$/i', "", basename($_FILES['newXML']['name']));
						@unlink($dir. '/' .$file_name);
						if(!move_uploaded_file($_FILES['newXML']["tmp_name"], $dir. '/' .$file_name)) {
							echo("File could not be saved. " . $uploadErrors[$_FILES['newXML']['error']]);
							exit(0);
						} else {
							$testXmlFile  = $dir. '/' .$file_name;
						}
					}
					$xmlName[0] = $testXmlFile;
					$xmlStr = file_get_contents($testXmlFile);
					if(!file_exists($dir . '/' .basename($xmlName[0]))) {
						touch($dir . '/' . basename($xmlName[0]));
						$handle = fopen($dir . '/' . basename($xmlName[0]), 'w+');
						fwrite($handle, $xmlStr);
						fclose($handle);
					}
					include_once("php/xml2array.php");
					$xml_debugger = new XML2Array();
					$arrXml = $xml_debugger->parse($xmlStr);
					if(!is_array($arrXml)) {
						$xmlStr = cu3er__our_fopen($testXmlFile);
						if($xmlStr == false) {
							$message .= $cu3er_messages['missingXML'];
							$continue = false;
						} else {
							$arrXml = $xml_debugger->parse($xmlStr);
						}
					}
					if($continue) {
						if(!is_array($arrXml)) {
							$message .= $cu3er_messages['notXML'];
						} else {
							cu3er__chmodDir($dir, 0777, 0777);
							$arrXml = cu3er__array_remove_empty($arrXml['data']);
							$xml['Slideshows'] = array(
								'width' => ($arrXml['project_settings']['width']['value'] != '') ? $arrXml['project_settings']['width']['value'] : $w,
								'height' => ($arrXml['project_settings']['height']['value'] != '') ? $arrXml['project_settings']['height']['value'] : $h,
								'background' => $arrXml['settings']['background']['color']['value'],
								'backgroundType' => ($arrXml['settings']['background']['color']['attr']['transparent'] == 'true') ? 'transparent' : 'color',
								'bg_use_image' => $arrXml['settings']['background']['image']['attr']['use_image'],
								'bg_align_to' => $arrXml['settings']['background']['image']['attr']['align_to'],
								'bg_align_pos' => $arrXml['settings']['background']['image']['attr']['align_pos'],
								'bg_x' => $arrXml['settings']['background']['image']['attr']['x'],
								'bg_y' => $arrXml['settings']['background']['image']['attr']['y'],
								'sdw_show' => $arrXml['settings']['shadow']['attr']['show'],
								'sdw_use_image' => $arrXml['settings']['shadow']['attr']['use_image'],
								'sdw_color' => $arrXml['settings']['shadow']['attr']['color'],
								'sdw_alpha' => $arrXml['settings']['shadow']['attr']['alpha'],
								'sdw_blur' => $arrXml['settings']['shadow']['attr']['blur'],
								'sdw_corner_tl' => $arrXml['settings']['shadow']['attr']['corner_TL'],
								'sdw_corner_tr' => $arrXml['settings']['shadow']['attr']['corner_TR'],
								'sdw_corner_bl' => $arrXml['settings']['shadow']['attr']['corner_BL'],
								'sdw_corner_br' => $arrXml['settings']['shadow']['attr']['corner_BR'],
								'pr_image' => $arrXml['preloader']['image']['url']['value'],
								'pr_align_to' => $arrXml['preloader']['image']['attr']['align_to'],
								'pr_align_pos' => $arrXml['preloader']['image']['attr']['align_pos'],
								'pr_x' => $arrXml['preloader']['image']['attr']['x'],
								'pr_y' => $arrXml['preloader']['image']['attr']['y'],
								'pr_scaleX' => $arrXml['preloader']['image']['attr']['scaleX'],
								'pr_scaleY' => $arrXml['preloader']['image']['attr']['scaleY'],
								'pr_loader_direction' => $arrXml['preloader']['image']['attr']['loader_direction'],
								'pr_alpha_loader' => $arrXml['preloader']['image']['attr']['alpha_loader'],
								'pr_alpha_bg' => $arrXml['preloader']['image']['attr']['alpha_bg'],
								'pr_tint_loader' => $arrXml['preloader']['image']['attr']['tint_loader'],
								'pr_tint_bg' => $arrXml['preloader']['image']['attr']['tint_bg'],
								'xml_location' => $cu3er_pathDir . '/' . basename($xmlName[0]),
								'modified' => date('Y-n-d H:i:s'),
								'id' => $_GET['id']
							);
							foreach($xml['Slideshows'] as $key=>$value) {
								if($value == '') {
									$xml['Slideshows'][$key] = 'emp7y';
								}
							}
							if(cu3er__sql_magic($wpdb->prefix . 'cu3er__slideshows', $xml['Slideshows'])) {
								$message .= $cu3er_messages['success'];
							} else {
								echo mysql_error();
							}
						}
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
			if(!is_numeric($slideshow['width']) || $slideshow['width'] < 1) {
				$message .= $cu3er_messages['oldXML'];
			}
			$slideshow['content'] = stripslashes($slideshow['content']);
			$query = mysql_query("SELECT * FROM `" . $wpdb->prefix . "cu3er__defaults` WHERE `slideshow_id`='".$_GET['id']."'");
			while($row = mysql_fetch_assoc($query)) {
				foreach($row as $key=>$value) {
					$row[$key] = stripslashes($value);
				}
				$default = cu3er__array_remove_empty($row);
			}
			$query = mysql_query("SELECT * FROM `" . $wpdb->prefix . "cu3er__slides` WHERE `slideshow_id`='".$_GET['id']."' ORDER BY `position` ASC");
			while($row = mysql_fetch_assoc($query)) {
				foreach($row as $key=>$value) {
					$row[$key] = stripslashes($value);
				}
				$slides[] = cu3er__array_remove_empty($row);
			}
			include_once($cu3er_path . 'tpl/edit.php');
		}
		elseif(is_numeric($_GET['id']) && $_GET['duplicate'] != 'true' && $_GET['type'] != 'xml') {
			if($_POST['Submit'] == 'Save Changes') {
				if(is_numeric($_POST['slideshow_id'])) {
					$_POST['default']['Defaults']['flipDirection'] = implode(",", $_POST['default']['Defaults']['flipDirection']);
					$cu3er_defaults = $_POST['default']['Defaults'];
					$cu3er_defaults['flipOrderFromCenter'] = (isset($cu3er_defaults['flipOrderFromCenter'])) ? 'true' : 'false';
					$cu3er_defaults['flipShader'] = (isset($cu3er_defaults['flipShader'])) ? 'flat' : 'none';
					if(!cu3er__sql_magic($wpdb->prefix . 'cu3er__defaults', $cu3er_defaults)) {
						$error = true;
						echo mysql_error();
					}
					mysql_query("DELETE FROM `" . $wpdb->prefix . "cu3er__slides` WHERE `slideshow_id`='".$_POST['slideshow_id']."'");
					foreach($_POST['slide'] as $slide) {
						$slide['slideshow_id'] = $_POST['slideshow_id'];
						$slide['flipDirection'] = implode(",", $slide['flipDirection']);
						$slide['flipOrderFromCenter'] = (isset($slide['flipOrderFromCenter'])) ? 'true' : 'false';
						$slide['flipShader'] = (isset($slide['flipShader'])) ? 'flat' : 'none';
						unset($slide['id']);
						foreach($slide as $key=>$value) {
							if($_POST['default']['Defaults'][$key] == $value) {
								unset($slide[$key]);
							}
						}
						if(!cu3er__sql_magic($wpdb->prefix . 'cu3er__slides', $slide)) {
							$error = true;
							echo mysql_error();
						}
					}
					$_POST['settings']['modified'] = date('Y-n-d H:i:s');
					if(!cu3er__sql_magic($wpdb->prefix . 'cu3er__slideshows', $_POST['settings'])) {

						$error = true;
						echo mysql_error();
					}
					if($error) {
						$message .= $cu3er_messages['error'];
					} else {
						$message .= $cu3er_messages['success'];
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
			if(!is_numeric($slideshow['width']) || $slideshow['width'] < 1) {
				$message .= $cu3er_messages['oldXML'];
			}
			$slideshow['content'] = stripslashes($slideshow['content']);
			$query = mysql_query("SELECT * FROM `" . $wpdb->prefix . "cu3er__defaults` WHERE `slideshow_id`='".$_GET['id']."'");
			while($row = mysql_fetch_assoc($query)) {
				foreach($row as $key=>$value) {
					$row[$key] = stripslashes($value);
				}
				$default = cu3er__array_remove_empty($row);
			}
			$query = mysql_query("SELECT * FROM `" . $wpdb->prefix . "cu3er__slides` WHERE `slideshow_id`='".$_GET['id']."' ORDER BY `position` ASC");
			while($row = mysql_fetch_assoc($query)) {
				foreach($row as $key=>$value) {
					$row[$key] = stripslashes($value);
				}
				$slides[] = cu3er__array_remove_empty($row);
			}
			include_once($cu3er_path . 'tpl/edit.php');
			
		} else {
			$query = mysql_query("SELECT * FROM `" . $wpdb->prefix . "cu3er__slideshows` ORDER BY `id` ASC") or die(mysql_error());
			while($row = mysql_fetch_assoc($query)) {
				foreach($row as $key=>$value) {
					$row[$key] = stripslashes($value);
				}
				$slideshows[] = $row;
			}
			include_once($cu3er_path . 'tpl/manage.php');
		}
	}
	
	function cu3er__action_register_tinymce() {	
		if(get_user_option('rich_editing') == 'true') {
			add_filter('mce_buttons', 'cu3er__filter_tinymce_button');
			add_filter('mce_external_plugins', 'cu3er__filter_tinymce_plugin');
		}
	}
	
	function cu3er__filter_tinymce_button($buttons) {
		array_push($buttons, '|', 'wpCU3ER' );
		return $buttons;
	}
	
	function cu3er__filter_tinymce_plugin($plugin_array) {
		$cu3er_path = trailingslashit(get_option('siteurl') . '/wp-content/plugins/wpcu3er');
		$plugin_array['wpCU3ER'] = $cu3er_path . 'js/editor_plugin.js';
		$plugin_array['contextmenu'] = $cu3er_path . 'mce/contextmenu/editor_plugin.js';
		return $plugin_array;
	}
	
	function cu3er_download() {
		global $wpdb;
		global $cu3er_messages;
		$ret = false;
		$uploadsDir = wp_upload_dir();
		$uplDir = $uploadsDir['path'] .'/';
		$save_path = $uploadsDir['url'] .'/';
		$cu3er = cu3er__our_fopen('http://205.186.133.41/CU3ER.swf');
		chmod($uplDir . 'CU3ER.swf', 0777);
		$handle = fopen($uplDir . 'CU3ER.swf', 'w+');
		if(fwrite($handle, $cu3er)) {
			$ret = true;
		}
		fclose($handle);
		$query = mysql_query("SELECT * FROM `" . $wpdb->prefix . "cu3er__settings` WHERE `id`=1");
		while($row = mysql_fetch_assoc($query)) {
			foreach($row as $key=>$value) {
				$row[$key] = stripslashes($value);
			}
			$settings = $row;
		}
		$settings['cu3er_location'] = $save_path . 'CU3ER.swf';
		cu3er__sql_magic($wpdb->prefix . 'cu3er__settings', $settings);
		return $ret;
	}	
	function cu3er__isHttpPath($url) {
		$cu3er_path = parse_url($url);
		return ($cu3er_path['scheme'] == 'http') ? true : ($cu3er_path['scheme'] == 'https') ? true : false;
	}	
	function cu3er__admin_setup() {
		global $wpdb;
		global $cu3er_messages;
		$uploadsDir = wp_upload_dir();
		$uplDir = $uploadsDir['url'] .'/';
		$save_path = $uploadsDir['path'] .'/';
		$message .= cu3er__admin_checkCu3er();
		if($_POST['Submit'] == 'Save Changes' || $_POST['Submit'] == 'Save Licence') {
			$upload_name = array(
				0 => "cu3er_location",
				1 => "js_location"
			);
			$uploadErrors = array(
				0 => "There is no error, the file uploaded with success",
				1 => "The uploaded file exceeds the upload_max_filesize directive in php.ini",
				2 => "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form",
				3 => "The uploaded file was only partially uploaded",
				4 => "No file was uploaded",
				6 => "Missing a temporary folder"
			);
			$_POST['settings']['licence'] = ($_POST['settings']['licence'] != '') ? $_POST['settings']['licence'] : ' ';

			foreach($upload_name as $key=>$name) {
				if($_FILES[$name]["tmp_name"] != '') {
			 	$file_name = preg_replace('/[^.A-Z0-9_ !@#$%^&()+={}\[\]\',~`-]|\.+$/i', "", basename($_FILES[$name]['name']));
					@unlink($save_path.$file_name);
					if(!move_uploaded_file($_FILES[$name]["tmp_name"], $save_path.$file_name)) {
						echo("File could not be saved. " . $uploadErrors[$_FILES[$name]['error']]);
						exit(0);
					} else {
						$_POST['settings'][$name] = $uplDir.$file_name;
					}
				}
			}
			cu3er__sql_magic($wpdb->prefix . "cu3er__settings", $_POST['settings']) or die(mysql_error());
			$message .= $cu3er_messages['setupSuccess'];
		}
		$query = mysql_query("SELECT * FROM `" . $wpdb->prefix . "cu3er__settings` WHERE `id`=1");
		while($row = mysql_fetch_assoc($query)) {
			foreach($row as $key=>$value) {
				$row[$key] = stripslashes($value);
			}
			$settings = $row;
		}
		include_once('tpl/setup.php');
	}
	
	function cu3er__action_load_scripts() {
		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('jquery-ui-sortable');
		wp_enqueue_script('jquery');
		wp_enqueue_script('swfobject');
		wp_enqueue_script('thickbox');
		wp_enqueue_script('media-upload');
		wp_enqueue_script('farbtastic');
	}	
	function cu3er__action_load_styles() {
		if(strpos($_SERVER['PHP_SELF'], 'wp-admin') !== false) {
			wp_admin_css( 'dashboard' );
			wp_enqueue_style('thickbox');
			wp_enqueue_style('farbtastic');
			$page = (isset($_GET['page'])) ? $_GET['page'] : '';
			if(preg_match('/CU3ER/i', $page)) {
				wp_enqueue_style('cu3er', plugins_url('/css/style.css', __FILE__));
			}
		}
	}	
	function cu3er__my_myme_types($mime_types){
		$mime_types['xml'] = 'text/xml'; // Adding xml extension
		$mime_types['js'] = 'text/javascript'; // Adding js extension
		return $mime_types;
	}
	function cu3er__action_activate() {
		global $wpdb;
		$q1 = mysql_query("SELECT * FROM `" . $wpdb->prefix . "cu3er__settings`");
		if(!$q1) {
			$cu3er__collate = ' COLLATE utf8_general_ci';
			$sql0 = "CREATE TABLE `" . $wpdb->prefix . "cu3er__slideshows` (
					`id` int(11) NOT NULL AUTO_INCREMENT,
					`name` varchar(255) NOT NULL,
					`width` int(11) NOT NULL,
					`height` int(11) NOT NULL,
					`type` varchar(20) NOT NULL,
					`background` varchar(20) NOT NULL,
					`images_folder` varchar(255) NOT NULL,
					`fonts_folder` varchar(255) NOT NULL,
					`content` text NOT NULL,
					`xml_location` varchar(255) NOT NULL,
					`bg_image` varchar(255) DEFAULT NULL,
					`bg_align_to` varchar(10) DEFAULT NULL,
					`bg_align_pos` varchar(10) DEFAULT NULL,
					`bg_x` varchar(50) DEFAULT NULL,
					`bg_y` varchar(50) DEFAULT NULL,
					`bg_scaleX` varchar(50) DEFAULT NULL,
					`bg_scaleY` varchar(50) DEFAULT NULL,
					`bg_use_image` varchar(15) DEFAULT NULL,
					`backgroundType` varchar(20) DEFAULT NULL,
					`sdw_show` varchar(20) DEFAULT NULL,
					`sdw_use_image` varchar(20) DEFAULT NULL,
					`sdw_color` varchar(20) DEFAULT NULL,
					`sdw_alpha` varchar(50) DEFAULT NULL,
					`sdw_blur` varchar(50) DEFAULT NULL,
					`sdw_corner_tl` varchar(30) DEFAULT NULL,
					`sdw_corner_tr` varchar(30) DEFAULT NULL,
					`sdw_corner_bl` varchar(30) DEFAULT NULL,
					`sdw_corner_br` varchar(30) DEFAULT NULL,
					`sdw_image` varchar(255) DEFAULT NULL,
					`pr_image` varchar(255) DEFAULT NULL,
					`pr_align_to` varchar(20) DEFAULT NULL,
					`pr_align_pos` varchar(20) DEFAULT NULL,
					`pr_x` varchar(50) DEFAULT NULL,
					`pr_y` varchar(50) DEFAULT NULL,
					`pr_scaleX` varchar(50) DEFAULT NULL,
					`pr_scaleY` varchar(50) DEFAULT NULL,
					`pr_loader_direction` varchar(20) DEFAULT NULL,
					`pr_alpha_loader` varchar(50) DEFAULT NULL,
					`pr_alpha_bg` varchar(5) DEFAULT NULL,
					`pr_tint_loader` varchar(15) DEFAULT NULL,
					`pr_tint_bg` varchar(15) DEFAULT NULL,
					`modified` datetime,
					PRIMARY KEY (`id`)
				)";

			$sql1 = "CREATE TABLE `" . $wpdb->prefix . "cu3er__slides` (
					`id` int(11) NOT NULL AUTO_INCREMENT,
					`slideshow_id` int(11) NOT NULL,
					`duration` varchar(50) DEFAULT NULL,
					`color` varchar(20) DEFAULT NULL,
					`caption` varchar(255) DEFAULT NULL,
					`link` varchar(255) DEFAULT NULL,
					`target` varchar(20) DEFAULT NULL,
					`heading` text,
					`paragraph` text,
					`dlink` varchar(255) DEFAULT NULL,
					`dtarget` varchar(20) DEFAULT NULL,
					`type` varchar(5) DEFAULT NULL,
					`columns` int(11) DEFAULT NULL,
					`rows` int(11) DEFAULT NULL,
					`type2d` varchar(50) DEFAULT NULL,
					`flipAngle` int(11) DEFAULT NULL,
					`flipOrder` int(11) DEFAULT NULL,
					`flipShader` varchar(50) DEFAULT NULL,
					`flipOrderFromCenter` varchar(20) DEFAULT NULL,
					`flipDirection` varchar(50) DEFAULT NULL,
					`flipColor` varchar(30) DEFAULT NULL,
					`flipBoxDepth` varchar(50) DEFAULT NULL,
					`flipDepth` varchar(50) DEFAULT NULL,
					`flipEasing` varchar(50) DEFAULT NULL,
					`flipDuration` varchar(50) DEFAULT NULL,
					`flipDelay` varchar(50) DEFAULT NULL,
					`flipDelayRandomize` varchar(50) DEFAULT NULL,
					`position` int(11) NOT NULL,
					`image` varchar(255) DEFAULT NULL,
					`align_pos` varchar(255) DEFAULT NULL,
					`x` varchar(50) DEFAULT NULL,
					`y` varchar(50) DEFAULT NULL,
					`scaleX` varchar(50) DEFAULT NULL,
					`scaleY` varchar(50) DEFAULT NULL,
					PRIMARY KEY (`id`)
				)";
			
			$sql2 = "CREATE TABLE `" . $wpdb->prefix . "cu3er__defaults` (
					`id` int(11) NOT NULL AUTO_INCREMENT,
					`slideshow_id` int(11) NOT NULL,
					`duration` varchar(50) DEFAULT NULL,
					`color` varchar(20) DEFAULT NULL,
					`caption` varchar(255) DEFAULT NULL,
					`link` varchar(255) DEFAULT NULL,
					`target` varchar(20) DEFAULT NULL,
					`heading` text,
					`paragraph` text,
					`dlink` varchar(255) DEFAULT NULL,
					`dtarget` varchar(20) DEFAULT NULL,
					`type` varchar(5) DEFAULT NULL,
					`columns` int(11) DEFAULT NULL,
					`rows` int(11) DEFAULT NULL,
					`type2d` varchar(50) DEFAULT NULL,
					`flipAngle` int(11) DEFAULT NULL,
					`flipOrder` int(11) DEFAULT NULL,
					`flipShader` varchar(50) DEFAULT NULL,
					`flipOrderFromCenter` varchar(20) DEFAULT NULL,
					`flipDirection` varchar(50) DEFAULT NULL,
					`flipColor` varchar(30) DEFAULT NULL,
					`flipBoxDepth` varchar(50) DEFAULT NULL,
					`flipDepth` varchar(50) DEFAULT NULL,
					`flipEasing` varchar(50) DEFAULT NULL,
					`flipDuration` varchar(50) DEFAULT NULL,
					`flipDelay` varchar(50) DEFAULT NULL,
					`flipDelayRandomize` varchar(50) DEFAULT NULL,
					`align_pos` varchar(5) DEFAULT NULL,
					`x` varchar(50) DEFAULT NULL,
					`y` varchar(50) DEFAULT NULL,
					`scaleX` varchar(50) DEFAULT NULL,
					`scaleY` varchar(50) DEFAULT NULL,
					`salign_pos` varchar(5) DEFAULT NULL,
					`sx` varchar(50) DEFAULT NULL,
					`sy` varchar(50) DEFAULT NULL,
					`swidth` varchar(50) DEFAULT NULL,
					`sheight` varchar(50) DEFAULT NULL,
					PRIMARY KEY (`id`)
				)";
			
			
			$sql3 = "CREATE TABLE `" . $wpdb->prefix . "cu3er__settings` (
					`id` int(11) NOT NULL AUTO_INCREMENT,
					`cu3er_location` varchar(255) NOT NULL,
					`licence` varchar(255) NOT NULL,
					`js_location` varchar(255) NOT NULL,
					`version` varchar(10) NOT NULL DEFAULT '0.22',
					PRIMARY KEY (`id`)
				)";
			$sql4 = "INSERT INTO `" . $wpdb->prefix . "cu3er__settings` (`id`,`version`) VALUES (1,'0.23') ON DUPLICATE KEY UPDATE `version`='0.23'";


			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql0.$cu3er__collate);
			dbDelta($sql1.$cu3er__collate);
			dbDelta($sql2.$cu3er__collate);
			dbDelta($sql3.$cu3er__collate);
			dbDelta($sql4);
		}

	}	
	function cu3er__checkDatabaseForUpdate() {
		global $wpdb;
		$sql1 = "SELECT `version` FROM `" . $wpdb->prefix . "cu3er__settings` WHERE `id`=1";
		$q = mysql_query($sql1);
		$r = mysql_fetch_assoc($q);
		switch($r['version']) {
			case '0.1b':
				mysql_query("ALTER TABLE `" . $wpdb->prefix . "cu3er__slideshows` ADD COLUMN `bg_scaleX` varchar(50) AFTER `bg_use_image`, ADD COLUMN `bg_scaleY` varchar(50) AFTER `bg_scaleX`;");
				mysql_query("ALTER TABLE `" . $wpdb->prefix . "cu3er__settings` CHARACTER SET utf8 COLLATE utf8_general_ci;");
				mysql_query("ALTER TABLE `" . $wpdb->prefix . "cu3er__defaults` CHARACTER SET utf8 COLLATE utf8_general_ci;");
				mysql_query("ALTER TABLE `" . $wpdb->prefix . "cu3er__slideshows` CHARACTER SET utf8 COLLATE utf8_general_ci;");
				mysql_query("ALTER TABLE `" . $wpdb->prefix . "cu3er__slides` CHARACTER SET utf8 COLLATE utf8_general_ci;");
				mysql_query("INSERT INTO `" . $wpdb->prefix . "cu3er__settings` (`id`,`version`) VALUES (1,'0.23') ON DUPLICATE KEY UPDATE `version`='0.23'");
				break;
			case '0.22':
				mysql_query("ALTER TABLE `" . $wpdb->prefix . "cu3er__settings` CHARACTER SET utf8 COLLATE utf8_general_ci;");
				mysql_query("ALTER TABLE `" . $wpdb->prefix . "cu3er__defaults` CHARACTER SET utf8 COLLATE utf8_general_ci;");
				mysql_query("ALTER TABLE `" . $wpdb->prefix . "cu3er__slideshows` CHARACTER SET utf8 COLLATE utf8_general_ci;");
				mysql_query("ALTER TABLE `" . $wpdb->prefix . "cu3er__slides` CHARACTER SET utf8 COLLATE utf8_general_ci;");
				mysql_query("INSERT INTO `" . $wpdb->prefix . "cu3er__settings` (`id`,`version`) VALUES (1,'0.23') ON DUPLICATE KEY UPDATE `version`='0.23'");
			case '0.23':
				// next version update will come here //
				break;
		}
	}
	function cu3er__action_uninstall() {
		global $wpdb;
		mysql_query("DROP TABLE `" . $wpdb->prefix . "cu3er__settings`");
		mysql_query("DROP TABLE `" . $wpdb->prefix . "cu3er__defaults`");
		mysql_query("DROP TABLE `" . $wpdb->prefix . "cu3er__slideshows`");
		mysql_query("DROP TABLE `" . $wpdb->prefix . "cu3er__slides`");
	}	
	function display_cu3er($slideshow, $ret = false) {
		if(is_numeric($slideshow)) {
			global $wpdb;
			// embeded code for showing cu3er //
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
			if($arrXml['settings']['folder_images'] == '<![CDATA[]]>') {
				$arrXml['settings']['folder_images'] = '';
			}
			$arrXml['settings']['folder_fonts'] = "<![CDATA[" .$slideshowS['fonts_folder']. "]]>";
			if($arrXml['settings']['folder_fonts'] == '<![CDATA[]]>') {
				$arrXml['settings']['folder_fonts'] = '';
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
				if($arrXml['slides'][$key]['slide']['link']['vle'] == '<![CDATA[]]>') {
					$arrXml['slides'][$key]['slide']['link']['vle'] = '';
				}
				if($value['target'] == $default['target']) {
					$arrXml['slides'][$key]['slide']['link']['@attributes']['target'] = $value['target'];
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
			if($settings['licence'] != '') {
				$data['data']['licence'] = '<![CDATA['. $settings['licence'] .']]>';
			}
			
			if(cu3er__url_exists($settings['js_location'])) {
				wp_enqueue_script('cu3er', $settings['js_location']);
				echo '<script type="text/javascript" src="'.cu3er__removeDomainName($settings['js_location']).'"></script>';
				$cObject = "var CU3ER_object = new CU3ER('CU3ER".$slideshow."');";
			}
			$params = ($slideshowS['backgroundType'] == 'transparent') ? "wmode : 'transparent'" : "bgcolor : '".str_replace('0x', '#', $slideshowS['background'])."'";
			$var = "<script type='text/javascript'>
				var vars = { xml_location : '".cu3er__removeDomainName($slideshowS['xml_location'])."', xml_encoded: '".urlencode(cu3er__array2xml($data))."' };
				// add Flash embedding parameters, etc. wmode, bgcolor...
				var params = { ". $params ." };
				// Flash object attributes id and name
				var attributes = { id:'CU3ER".$slideshow."', name:'CU3ER".$slideshow."' };
				swfobject.embedSWF('". cu3er__removeDomainName($settings['cu3er_location']) ."?" . time() . "', 'CU3ER".$slideshow."', ". $slideshowS['width'] .", ". $slideshowS['height'] .", '10.0.0', 'js/expressinstall.swf', vars, params, attributes );
				".$cObject."
			</script>" . $slideshowS['content'];
			if($ret) {
				return $var;
			} else {
				echo '<div id="CU3ER'.$slideshow.'">' . $var . '</div>';
			}
		}
	}	
	
	function CU3ER_func($atts) {
		global $wpdb;
		extract(shortcode_atts(array(
			'slider' => false
			), $atts));
		if($slider) {
			return display_cu3er($slider, true);
		} else {
			return false;
		}
	}
	function cu3er__array_remove_empty($input){
		return cu3er__cleanUpArray($input);
	}
	
	function cu3er__cleanUpArray($arr, $non_zero_start = 0) {
		$main = array('transition', 'slide');
		$new_arr = array();
		foreach ($arr as $key => $value) {
			if (isset($value)) {
				if (is_array($value)) {
					$value = cu3er__cleanUpArray($value, $non_zero_start);
					// If after the cleaning there are not elements do not bother to add this item
					if (count($value) == 0) {
						if(!in_array($key, $main)) {
							continue;
						} else {
							$new_arr[$key] = $value;
						}
					}
				} else {
					$value = trim($value);
					if(((string)$value == '' || (string)$value == null) && !in_array($key, $main)) {
						continue;
					}
				}
				$new_arr[$key] = $value;
			} else {
				if($value === 0 || in_array($key, $main)) {
					$new_arr[$key] = $value;
				}
			}
		}
		// Reordering elements
		if (!empty($new_arr)) {
			if (!empty($non_zero_start)) {
				$new_arr = array_merge_recursive(array("") + $new_arr);
				unset($new_arr[0]);
			} else {
				$new_arr = array_merge_recursive($new_arr);
			}
		}
		return $new_arr;
	}
	
	
	function cu3er__getAttributes($array, $parent) {
		foreach($array as $key=>$value) {
			if($key === '@attributes') {
				foreach($value as $k=>$v) {
					$attr[] = $k . '="' . $v . '"';
				}
				$attributes[$parent] = implode(" ", $attr);
				unset($attr);
			}
		}
		return $attributes;
	}
	function cu3er__getNodeValue($array) {
		foreach($array as $key=>$value) {
			if($key === 'vle') {
				return $value;
			}
		}
		return '';
	}
	function cu3er__array2xml($array) {
		foreach($array as $key=>$value) {
			if(is_array($value)) {
				if($key !== '@attributes' && $key !== 'vle') {
					if(is_numeric($key)) {
						$childs = '';
						$children = cu3er__array2xml($value);
					} else {
						$childs .= cu3er__array2xml($value);
						$childs = ($childs != '') ? $childs : 'empty';
					}
				}
			} else {
				$childs = ($value != '') ? $value : 'empty';
			}
			if($childs != '') {
				$attributes = cu3er__getAttributes($array[$key], $key);
				$vle = cu3er__getNodeValue($array[$key]);
				$attributes[$key] = ($attributes[$key] != '') ? ' ' . $attributes[$key] : '';
				if($key !== 'vle' && $key !== '@attributes') {
					if($childs == 'empty') {
						if($vle != '') {
							$xml .= '<'. $key . $attributes[$key] .'>' . $vle . '</'. $key .'>';
						} else {
							$xml .= '<'. $key . $attributes[$key] .' />';
						}
					} else {
						$xml .= '<'. $key . $attributes[$key] .'>' . $vle . $childs . '</'. $key .'>';
					}
					unset($attributes[$key], $childs);
				}
			}
			if($children != '') {
				$xml .= $children;
				$children = '';
			}
		}
		return $xml;
	}
	function cu3er__isImage($img) {
		if($img != '') {
			$handle = cu3er__our_fopen($img);
			if($handle != '') {
				return true;
			}
		}
		return false;
	}
	function cu3er__sql_magic($table, $data, $type = null) {
		
		$unexpected = array('Submit','MAX_FILE_SIZE','submit_x','submit_y','filePath', 'act');

		if($type == 'del') {

			foreach($data as $key=>$value) {
				if(!in_array($key,$unexpected)) $qstr[] = "`".$key."` = '".mysql_real_escape_string($value)."'";
			}
			$qstr   = @implode(" AND ",$qstr);
			return (mysql_query("DELETE FROM `".$table."` WHERE ".$qstr)) ? true : false;

		} else {

			foreach($data as $key=>$value) {
				if($value != '') {
					if($value == 'emp7y') $value = '';
					if(!in_array($key, $unexpected)) (preg_match('{^\S{0,1}id$}', $key)) ? $str = "`".$key."`='".mysql_real_escape_string($value)."'" : $qstr[] = "`".$key."` = '".mysql_real_escape_string($value)."'";
				}
			}
			$qstr   = @implode(",",$qstr);

			if(!empty($str)) {
				return (mysql_query("UPDATE `".$table."` SET ".$qstr." WHERE ".$str)) ? true : false;
			} else {
				return (mysql_query("INSERT INTO `".$table."` SET ".$qstr)) ? true : false;
			}
		}
	}
	function cu3er__removeDomainName($string) {
		return preg_replace('/http\:\/\/(.*?)\//', '/', $string);
	}
	function cu3er__ffilesize($file){
		if(CU3ER_isCurlInstalled()) {
			$ch = @curl_init($file);
			@curl_setopt($ch, CURLOPT_FAILONERROR, true);
			@curl_setopt($ch, CURLOPT_NOBODY, true);
			@curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			@curl_setopt($ch, CURLOPT_HEADER, true);
			@curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 4);
			$data = @curl_exec($ch);
			@curl_close($ch);
			if($data === false) return false;
			if (preg_match('/Content-Length: (\d+)/', $data, $matches)) return (float)$matches[1];
		} else {
			return @filesize($file);
		}
		return false;
	}
	function cu3er__our_fopen($url) {
		if(CU3ER_isCurlInstalled()) {
			$ch = curl_init();
			@curl_setopt($ch, CURLOPT_FAILONERROR, true);
			@curl_setopt($ch, CURLOPT_URL, $url);
			@curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			@curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			@curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 4);
			$contents = curl_exec($ch);
			curl_close($ch);
		} else {
			$contents = file_get_contents($url);
		}
		return ($contents != '') ? $contents : false;

	}
	function pre() {
		$out = '<pre>';
		foreach (func_get_args() as $arg) $out .= sprintf("\n%s\n", print_r($arg, true));
		$out .= '</pre>';
		return $out;
	}
	
	function cu3er__getRand($loc) {
		$rand = mt_rand(1,10000);
		if(!is_dir($loc . $rand)) {
			mkdir($loc . $rand);
			chmod($loc . $rand, 0777);
			return $rand;
		} else {
			cu3er__getRand($loc);
		}
	}
	function CU3ER_isCurlInstalled() {
		if(in_array('curl', get_loaded_extensions())) {
			return true;
		} else{
			return false;
		}
	}
	
	function cu3er__chmodDir($path = '.', $dirMod = '0755', $fileMod = '0644'){  

		$ignore = array("cgi-bin", ".", "..");
		$extensionAllow = array('xml', 'jpg', 'jpeg', 'gif', 'png', 'swf');

		$dh = @opendir($path);

		while(false !== ($file = readdir($dh))) {
			if(in_array(end(explode(".", $file)), $extensionAllow) || is_dir($path.'/'.$file)) {
				if(!in_array($file, $ignore)) {
					if(is_dir($path. "/" .$file)) {
						chmod($path. "/" .$file, $dirMod);
						cu3er__chmodDir($path. "/" .$file, $dirMod, $fileMod);
					} else {
						chmod($path. "/" .$file, $fileMod);
					}
				}
			}
		}

		closedir($dh);

	}
	
	register_activation_hook(__FILE__, 'cu3er__action_activate');		// on install create everything needed for plugin to work correctly 
	register_uninstall_hook(__FILE__, 'cu3er__action_uninstall');		// on unistall delete all databases 
	add_action('admin_menu', 'cu3er__admin_actions');		// create menus for CU3ER 
	add_action('init', 'cu3er__action_load_scripts');			// loads required scripts 
	add_action('init', 'cu3er__action_load_styles');			// loads required styles 
	add_filter('upload_mimes', 'cu3er__my_myme_types', 1, 1);		// allowing xml to be uploaded with media library 
	add_action('init', 'cu3er__action_register_tinymce');			// registers tinymce button and menu 
	add_action('init', 'cu3er__checkDatabaseForUpdate');			// registers tinymce button and menu 
	add_shortcode('CU3ER', 'CU3ER_func');				// CU3ER shortcode 
?>