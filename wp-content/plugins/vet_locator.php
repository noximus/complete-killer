<?php
/*
Plugin Name: Vet Locator Interface
Plugin URI: 
Description: Interface with the Vet Locater service and display the results.
Author: 
Version: 1
Author URI: 
*/


	class Vet_Locator {
	
		var $list_url = "https://vetlocator.merial.com/clinics/list";
		var $count_url = "https://vetlocator.merial.com/clinics/count";
		var $api_key = "bfa86e89-44f0-4d6f-ad34-8b850bf2a80e";
		
		public function test_output() {
			$str_html .= "\t<div id='locator_results'>\n";
			$str_html .= "\t\t<div id='locator_results_header'>\n";
			$str_html .= "\t\t\tResults in: <strong>Test</strong>\n";
			$str_html .= "\t\t</div>\n";
			$str_html .= "\t\t<div id='Searchresult'>\n";
			$str_html .= "\t\t</div>\n";

			$str_html .= "\t\t<div id=''>";
			$str_html .= "\t\t<div class='result'>\n";
			$str_html .= "\t\t\t<div class='results_record'>\n";
			$str_html .= "<form action='/coupon.php' method='POST' target='_blank'>";
			$str_html .= "\t\t\t\t<strong>Clinic Name:</strong><br />\n";
			$str_html .= "\t\t\t\tTest Clinic<br />\n\t\t\t\t<br />\n";
			$str_html .= "\t\t\t\t<strong>Address:</strong><br />\n";
			$str_html .= "\t\t\t\t123 Address St. Test, US 01010<br />\n\t\t\t\t<br />\n";				
			$str_html .= "\t\t\t\t<strong>Phone:</strong><br />\n";
			$str_html .= "\t\t\t\t123-456-7890<br />\n\t\t\t\t<br />\n";					
			$str_html .= "
				<input type='hidden' name='ClinicName' id='ClinicName' value='Test Clinic' />
				<input type='hidden' name='Address' id='Address' value='123 Address St.' />
				<input type='hidden' name='Phone' id='Phone' value='123-456-7890' />
				<input type='hidden' name='City' id='City' value='Test' />
				<input type='hidden' name='State' id='State' value='US' />
				<input type='hidden' name='ZipCode' id='ZipCode' value='01010' />";
			$str_html .= "\t\t\t\t<a href='javascript: submitform();' class='get-coupon'>Get Coupon</a><br />\n";					
			$str_html .= "</form>"; 		
			$str_html .= "\t\t\t</div>\n";
			$str_html .= "\t\t</div>\n\t</div>";
			$str_html .= "\t\t</div>\n";

			return $str_html;			
		}
		
		public function lookup_clinics($clinic_name = FALSE, $city = FALSE, $state = FALSE, $zip_code = FALSE) {
			
			if (!$clinic_name && !$city && !$state && !$zip_code) {
				return FALSE;
			}

			$url = $this->list_url ."?apiKey=" .$this->api_key .($clinic_name ? "&clinicName=" .urlencode($clinic_name) : "") .($city ? "&city=" .urlencode($city) : "")  .(($state && $state != '--')? "&state=" .urlencode($state) : "") .($zip_code ? "&zipCode=" .urlencode($zip_code) : "");
			
			//$url = "http://www.google.com";
			
			
			
			$curl_handle = curl_init($url);
			curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl_handle, CURLOPT_HEADER, 0);
			curl_setopt($curl_handle, CURLOPT_TIMEOUT, 2);
			curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl_handle, CURLOPT_FAILONERROR, false);
			function recordError( $str )
				{
					$File = "errorLog.txt";
					$Handle = fopen( $File, 'w' );
					
					$message = "--------------------------------------------------"."\r\n";
					$message .= $str."\r\n";
					$message .= "--------------------------------------------------"."\r\n";
					
					fclose($Handle);
				}
							
			if(curl_exec($curl_handle) === false)
				{
					//echo 'Curl error: ' . curl_error($curl_handle);
   					recordError( curl_error($curl_handle) );
					
					$curl_handle = curl_init($url);
					curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($curl_handle, CURLOPT_HEADER, 0);
					curl_setopt($curl_handle, CURLOPT_TIMEOUT, 2);
					curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, false);
					curl_setopt($curl_handle, CURLOPT_FAILONERROR, false);
					//testWorking();
				}
					else
				{
					$results = @curl_exec($curl_handle);
					curl_close($curl_handle);
				}
			
			
			
			$str_html = "";
			
			
			
			if (!$results) {
				
				$str_html .= "\t<div id='locator_results'>\n";
				$str_html .= "\t\t<div id='locator_results_header'>\n";

				$str_html .= "\t\t\tResults in: <strong>" .($clinic_name ? htmlspecialchars($clinic_name, ENT_QUOTES) : "") .($clinic_name && ($city || $state || $zip_code) ? ", " : "") .($city ? htmlspecialchars($city, ENT_QUOTES) : "")  .(($clinic_name || $city) && ($state || $zip_code) ? ", " : "")  .($state ? htmlspecialchars($state, ENT_QUOTES) : "") .(($clinic_name || $city || $state) && ($zip_code) ? ", " : "") .($zip_code ? htmlspecialchars($zip_code, ENT_QUOTES) : "") ."</strong>\n";
				$str_html .= "\t\t</div>\n";

				$str_html .= "\t\t<div class='results_cell cell_1'>\n";
				$str_html .= "\t\t</div>\n\t</div>";
				
			} else {
				$xml = simplexml_load_string($results);
	
				$str_html .= "\t<div id='locator_results'>\n";
				$str_html .= "\t\t<div id='locator_results_header'>\n";
				$str_html .= "\t\t\tResults in: <strong>" .($clinic_name ? htmlspecialchars($clinic_name, ENT_QUOTES) : "") .($clinic_name && ($city || $state || $zip_code) ? ", " : "") .($city ? htmlspecialchars($city, ENT_QUOTES) : "")  .(($clinic_name || $city) && ($state || $zip_code) ? ", " : "")  .($state ? htmlspecialchars($state, ENT_QUOTES) : "") .(($clinic_name || $city || $state) && ($zip_code) ? ", " : "") .($zip_code ? htmlspecialchars($zip_code, ENT_QUOTES) : "") ."</strong>\n";
				$str_html .= "\t\t</div>\n";
				$str_html .= "\t\t<div id='Searchresult'>\n";
				$str_html .= "\t\t</div>\n";

				$str_html .= "\t\t<div id='hiddenresult'>";
				$str_html .= "\t\t<div class='result'>\n";
				$count = count($xml);
				$counter = 0;
				
				
				if (is_object($xml)) {
	
					foreach ($xml as $clinic) {
						$counter++;
						$str_html .= "\t\t\t<div class='results_record'>\n";
						$str_html .= "<form action='/coupon.php' method='POST' target='_blank'>";
						$str_html .= "\t\t\t\t<strong>Clinic Name:</strong><br />\n";
						$str_html .= "\t\t\t\t" .(string)$clinic->ClinicName ."<br />\n\t\t\t\t<br />\n";
						$str_html .= "\t\t\t\t<strong>Address:</strong><br />\n";
						$str_html .= "\t\t\t\t" .(string)$clinic->Address ." " .(string)$clinic->City .", " .(string)$clinic->State .", " .(string)$clinic->ZipCode  ."<br />\n\t\t\t\t<br />\n";				
						$str_html .= "\t\t\t\t<strong>Phone:</strong><br />\n";
						$phone = (string)$clinic->PhoneNumber;
						$str_html .= "\t\t\t\t" .substr($phone, 0, 3) ."-" .substr($phone, 3, 3) ."-" .substr($phone, 6, 4) ."<br />\n\t\t\t\t<br />\n";					
						$str_html .= "<input type='hidden' name='ClinicName' id='ClinicName' value='" .(string)$clinic->ClinicName ."' />\n";
						$str_html .= "<input type='hidden' name='Address' id='Address' value='" .(string)$clinic->Address ."' />\n";
						$str_html .= "<input type='hidden' name='Phone' id='Phone' value='" .substr($phone, 0, 3) ."-" .substr($phone, 3, 3) ."-" .substr($phone, 6, 4) ."' />\n";
						$str_html .= "<input type='hidden' name='City' id='City' value='" .(string)$clinic->City ."' />\n";
						$str_html .= "<input type='hidden' name='State' id='State' value='" .(string)$clinic->State ."' />\n";
						$str_html .= "<input type='hidden' name='ZipCode' id='ZipCode' value='" .(string)$clinic->ZipCode  ."' />";						
						$str_html .= "\t\t\t\t<a href='#' onClick='$(this).parent().submit();' class='get-coupon'>Get Coupon</a><br />\n";					
						$str_html .= "</form>"; 		
						$str_html .= "\t\t\t</div>\n";


						// Remove these lines if you want all the results in 1 cell, instead of 2 cells.
						if (($counter % 6) == 0) {
							$str_html .= "\t\t</div>\n\t\t<div class='result'>\n";
						}
					}
				}
				$str_html .= "\t\t</div>\n\t</div>";
			}
				$str_html .= "\t\t</div>\n";

			return $str_html;

		}
		
	}
 
 ?>
